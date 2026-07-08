<?php

namespace App\Console\Commands;

use App\Services\YandexFormsApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestYandexApi extends Command
{
    protected $signature = 'app:test-yandex-api';
    protected $description = 'Тестирование API Яндекс Форм: проверка токена, организации, чтения и записи';

    public function handle(): int
    {
        $token = config('services.yandex.token');
        $orgId = config('services.yandex.org_id');
        $formId = '6a46534d90290237129cb245'; // Форма для тестирования

        $this->newLine();
        $this->info('=== Тест API Яндекс Форм ===');
        $this->newLine();

        // 1. Проверка конфигурации
        $this->line('1. Проверка конфигурации...');
        if (!$token) {
            $this->error('   YANDEX_OAUTH_TOKEN не задан в .env');
            return 1;
        }
        $this->info('   Token: ' . substr($token, 0, 15) . '...');
        $this->info('   Org ID: ' . ($orgId ?: 'не задан'));
        $this->info('   Form ID: ' . $formId);
        $this->newLine();

        // 2. Проверка токена (кому принадлежит)
        $this->line('2. Проверка владельца токена...');
        $userInfo = Http::withHeaders([
            'Authorization' => 'OAuth ' . $token,
        ])->timeout(10)->get('https://login.yandex.ru/info');

        if ($userInfo->successful()) {
            $user = $userInfo->json();
            $this->info('   Логин: ' . ($user['login'] ?? 'N/A'));
            $this->info('   ID: ' . ($user['id'] ?? 'N/A'));
            $this->info('   client_id: ' . ($user['client_id'] ?? 'N/A'));
        } else {
            $this->error('   Ошибка: ' . $userInfo->status() . ' ' . $userInfo->body());
        }
        $this->newLine();

        // 3. Тест записи (создание ответа)
        $this->line('3. Тест записи (createAnswer)...');
        $api = app(YandexFormsApi::class);
        $testData = [
            'event_id' => '1',
            'name' => 'API Test ' . date('H:i:s'),
            'email' => 'test-' . time() . '@test.com',
            'phone' => '+7999000' . rand(1000, 9999),
        ];

        $answer = $api->createAnswer($formId, $testData);
        if ($answer) {
            $answerId = $answer['answer_id'] ?? $answer['id'] ?? null;
            $answerKey = $answer['answer_key'] ?? null;
            $this->info('   ✅ Ответ создан! answer_id=' . $answerId);
            $this->info('   answer_key=' . substr($answerKey ?? '', 0, 20) . '...');
        } else {
            $this->error('   ❌ Не удалось создать ответ');
        }
        $this->newLine();

        // 4. Тест чтения одного ответа (правильный формат)
        $this->line('4. Тест чтения ответа (getAnswer)...');
        if ($answerId) {
            $readResponse = Http::withHeaders([
                'Authorization' => 'OAuth ' . $token,
                'X-Org-Id' => (string) $orgId,
            ])->timeout(10)->get('https://api.forms.yandex.net/v1/answers', [
                'answer_id' => $answerId,
            ]);

            $this->info('   Статус: ' . $readResponse->status());
            if ($readResponse->successful()) {
                $data = $readResponse->json();
                $this->info('   ✅ Ответ получен!');
                $this->info('   Данные: ' . json_encode($data['data'] ?? [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            } else {
                $body = $readResponse->json();
                $this->error('   ❌ Ошибка: ' . $readResponse->status());
                $this->error('   Сообщение: ' . ($body['detail'] ?? $readResponse->body()));
            }
        }
        $this->newLine();

        // 5. Тест чтения ответа через сервисный метод
        $this->line('5. Тест getAnswer() через сервис...');
        if ($answerId) {
            $serviceAnswer = $api->getAnswer($formId, (string) $answerId);
            if ($serviceAnswer) {
                $this->info('   ✅ Сервис вернул данные');
                $this->info('   Ключи: ' . implode(', ', array_keys($serviceAnswer)));
            } else {
                $this->error('   ❌ Сервис вернул null');
            }
        }
        $this->newLine();

        // 6. Тест списка ответов
        $this->line('6. Тест списка ответов (getAnswers)...');
        $listResponse = Http::withHeaders([
            'Authorization' => 'OAuth ' . $token,
            'X-Org-Id' => (string) $orgId,
        ])->timeout(10)->get("https://api.forms.yandex.net/v1/surveys/{$formId}/answers", [
            'page_size' => 5,
        ]);

        $this->info('   Статус: ' . $listResponse->status());
        if ($listResponse->successful()) {
            $list = $listResponse->json();
            $count = count($list['answers'] ?? []);
            $this->info('   ✅ Получено ответов: ' . $count);
        } else {
            $body = $listResponse->json();
            $this->error('   ❌ Ошибка: ' . $listResponse->status());
            $this->error('   Сообщение: ' . ($body['detail'] ?? $listResponse->body()));
        }
        $this->newLine();

        // Итог
        $this->info('=== Тест завершён ===');
        $this->newLine();

        return 0;
    }
}
