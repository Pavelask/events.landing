<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\URL;

class OnlyOfficeService
{
    protected string $url;

    protected string $secret;

    protected int $ttl;

    public function __construct()
    {
        $this->url = rtrim((string) config('onlyoffice.url'), '/');
        $this->secret = (string) config('onlyoffice.secret');
        $this->ttl = (int) config('onlyoffice.token_ttl', 3600);
    }

    /**
     * Адрес JS-API Document Server, который подключаем на страницу редактора.
     */
    public function apiJsUrl(): string
    {
        return $this->url . '/web-apps/apps/api/documents/api.js';
    }

    /**
     * Полный конфиг, который передаём в DocsAPI.DocEditor (без token).
     */
    public function editorConfig(DocumentTemplate $template): array
    {
        return [
            'document' => [
                'fileType' => 'docx',
                'key' => $this->documentKey($template),
                'title' => ($template->name ?: 'document') . '.docx',
                'url' => $this->documentUrl($template),
                'permissions' => [
                    'edit' => true,
                    'download' => true,
                    'print' => true,
                    'review' => false,
                    'chat' => false,
                ],
            ],
            'documentType' => 'word',
            'editorConfig' => [
                'mode' => 'edit',
                'lang' => 'ru',
                'callbackUrl' => $this->callbackUrl($template),
                'user' => [
                    'id' => (string) (auth()->id() ?? 0),
                    'name' => auth()->user()?->name ?? 'Administrator',
                ],
                'customization' => [
                    'features' => [
                        'spellcheck' => true,
                    ],
                    'autosave' => true,
                    'comments' => false,
                    'compactHeader' => false,
                    'feedback' => false,
                    'help' => false,
                    'uiTheme' => 'default-dark',
                ],
                'width' => '100%',
                'height' => '100%',
            ],
        ];
    }

    /**
     * Подписываем конфиг JWT (HS256) и возвращаем токен.
     */
    public function signConfig(array $config): ?string
    {
        if ($this->secret === '') {
            return null;
        }

        return $this->sign($config);
    }

    /**
     * URL документа, который Document Server скачает по GET.
     * Подписанный временный URL (аргуман no-auth, т.к. запрос идёт с сервера DOCS).
     */
    public function documentUrl(DocumentTemplate $template): string
    {
        $url = URL::signedRoute('onlyoffice.document', [
            'documentTemplate' => $template->getKey(),
        ], now()->addSeconds($this->ttl));

        // Для локального теста контейнер ДОЛЖЕН достучаться до сайта.
        // Если задан ONLYOFFICE_APP_URL, подменяем хост/порт, чтобы из
        // контейнера путь вёл на host.docker.internal (а не на localhost).
        if (config('onlyoffice.app_url')) {
            $public = rtrim((string) config('onlyoffice.app_url'), '/');
            if ($public !== rtrim(url('/'), '/')) {
                $url = preg_replace('#^https?://[^/]+#', $public, $url) ?? $url;
            }
        }

        return $url;
    }

    /**
     * Callback, куда Document Server вернёт сохранённый файл.
     * Абсолютный URL с точки зрения сервера (вне сессии/CSRF).
     */
    public function callbackUrl(DocumentTemplate $template): string
    {
        return rtrim((string) config('onlyoffice.app_url'), '/')
            . '/onlyoffice/callback/' . $template->getKey();
    }

    /**
     * Уникальный ключ документа. Меняется при изменении файла/шаблона,
     * чтобы OnlyOffice не отдавал устаревшую кэш-копию.
     *
     * Для сценария «один админ правит шаблон» добавляем временную метку —
     * каждое открытие создаёт свежую сессию и не цепляется за возможные
     * «битые» сессии прошлых открытий (актуально после падений docservice).
     */
    public function documentKey(DocumentTemplate $template): string
    {
        return md5(
            $template->getKey() . '|' .
            ($template->docx_file ?? '') . '|' .
            optional($template->updated_at)->getTimestamp() . '|' .
            now()->getTimestamp()
        );
    }

    /**
     * Проверяем callback-токен, которым OnlyOffice подписывает ответ.
     */
    public function validateCallback(?string $token): bool
    {
        if ($this->secret === '' || $token === null) {
            return false;
        }

        return $this->verify($token);
    }

    /*
    |--------------------------------------------------------------------------
    | JWT (HS256) — без внешних зависимостей
    |--------------------------------------------------------------------------
    */

    public function sign(array $payload): string
    {
        $header = $this->b64(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = now()->addSeconds($this->ttl)->getTimestamp();
        $body = $this->b64(json_encode($payload));
        $signature = $this->b64(hash_hmac('sha256', $header . '.' . $body, $this->secret, true));

        return $header . '.' . $body . '.' . $signature;
    }

    public function verify(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $body, $signature] = $parts;

        $expected = $this->b64(hash_hmac('sha256', $header . '.' . $body, $this->secret, true));
        if (!hash_equals($expected, $signature)) {
            return false;
        }

        $payload = json_decode($this->b64Decode($body), true);

        return isset($payload['exp']) && (int) $payload['exp'] >= now()->getTimestamp();
    }

    protected function b64(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function b64Decode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}