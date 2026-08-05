# Проблема принадлежности токена к организации Яндекс Форм

## Суть проблемы

OAuth-токен и форма принадлежат **разным организациям** в Яндекс 360. Из-за этого API чтения ответов возвращает ошибку, а API записи работает (публичный эндпоинт).

---

## Что работает (запись)

Отправка ответа в форму — публичный эндпоинт, не требует принадлежности к организации:

```php
// app/Services/YandexFormsApi.php — createAnswer()
public function createAnswer(string $formId, array $data): ?array
{
    $response = Http::withHeaders([
        'Authorization' => 'OAuth ' . $this->token,  // Токен: pavel.a.klimov@elprof.ru
        'Content-Type' => 'application/json',
    ])
    ->withBody(json_encode($data), 'application/json')
    ->timeout(30)
    // POST /v1/surveys/{formId}/form — публичный эндпоинт,任何人都可以 отправить ответ
    ->post("{$this->baseUrl}/surveys/{$formId}/form");

    // Возвращает: { answer_id: 2466704902, answer_key: "e7edb5c..." }
    // ✅ Работает! Данные появляются в Яндекс Формах
}
```

**Результат:** ответ успешно создаётся, `answer_id` возвращается.

---

## Что НЕ работает (чтение)

### 1. Получение одного ответа

```php
// app/Services/YandexFormsApi.php — getAnswer()
public function getAnswer(string $formId, string $answerId): ?array
{
    $response = Http::withHeaders([
        'Authorization' => 'OAuth ' . $this->token,
        // ❌ Неправильный заголовок: X-Cloud-Org-Id
        // Правильно по документации: X-Org-Id
    ])
    ->timeout(30)
    // ❌ Неправильный эндпоинт: /surveys/{formId}/answers/{answerId}
    // Правильно по документации: /answers?answer_id={answerId}
    ->get("{$this->baseUrl}/surveys/{$formId}/answers/{$answerId}");

    // Ответ: 404 Not Found
}
```

**Правильный формат по документации:**

```php
// GET https://api.forms.yandex.net/v1/answers?answer_id=2466704902
// С заголовком: X-Org-Id: 2188002634
$response = Http::withHeaders([
    'Authorization' => 'OAuth ' . $token,
    'X-Org-Id' => '2188002634',  // Правильный заголовок
])->get('https://api.forms.yandex.net/v1/answers', [
    'answer_id' => 2466704902,   // Как query parameter, не часть URL
]);
```

**Но даже с правильным форматом возвращает 404** — потому что токен не принадлежит организации формы.

### 2. Получение списка ответов

```php
// app/Services/YandexFormsApi.php — getAnswers()
public function getAnswers(string $formId, array $filters = []): array
{
    $response = Http::withHeaders($this->headers())
        ->timeout(30)
        ->get("{$this->baseUrl}/surveys/{$formId}/answers", [
            'page_size' => 100,
        ]);

    // Ответ: 400 Bad Request
    // Body: {"detail": "Требуется организация"}
}
```

**Правильный формат:**

```php
// GET https://api.forms.yandex.net/v1/surveys/{formId}/answers?page_size=50
// С заголовком: X-Org-Id: 2188002634
$response = Http::withHeaders([
    'Authorization' => 'OAuth ' . $token,
    'X-Org-Id' => '2188002634',
])->get("https://api.forms.yandex.net/v1/surveys/{$formId}/answers", [
    'page_size' => 50,
]);
```

**Но возвращает 400** — токен не в организации формы.

---

## Диагностический код

Тест для проверки проблемы ( можно запустить через `php artisan tinker` ):

```php
// Проверка 1: Кому принадлежит токен
$r = Http::withHeaders([
    'Authorization' => 'OAuth ' . config('services.yandex.token'),
])->get('https://login.yandex.ru/info');

echo 'Токен принадлежит: ' . $r->json()['login'] . PHP_EOL;
// Вывод: pavel.a.klimov@elprof.ru

// Проверка 2: Форма в какой организации
$formId = '6a46534d90290237129cb245';
$r = Http::withHeaders([
    'Authorization' => 'OAuth ' . config('services.yandex.token'),
    'X-Org-Id' => config('services.yandex.org_id'),  // 2188002634
])->get("https://api.forms.yandex.net/v1/surveys/{$formId}/answers", [
    'page_size' => 1,
]);

echo 'Статус: ' . $r->status() . PHP_EOL;
echo 'Ответ: ' . $r->body() . PHP_EOL;
// Вывод: 400 {"detail": "Требуетя организация"}
// Или: 400 {"detail": "Пользователь не принадлежит организации"}

// Проверка 3: Форма работает для записи (.pubличный эндпоинт)
$r = Http::withHeaders([
    'Authorization' => 'OAuth ' . config('services.yandex.token'),
    'Content-Type' => 'application/json',
])->withBody(json_encode([
    'event_id' => '1',
    'name' => 'Test',
    'email' => 'test@test.com',
]), 'application/json')
->post("https://api.forms.yandex.net/v1/surveys/{$formId}/form");

echo 'Запись: ' . $r->status() . ' answer_id=' . ($r->json()['answer_id'] ?? 'N/A') . PHP_EOL;
// Вывод: 200 answer_id=2466704902 ✅
```

---

## Организации в игре

| Элемент | Организация | Владелец |
|---------|-------------|----------|
| OAuth-токен | Не определена (личный аккаунт?) | pavel.a.klimov@elprof.ru |
| Форма `6a46534d90290237129cb245` | Организация формы | Создатель формы |
| `YANDEX_ORG_ID=2188002634` | Яндекс 360 для бизнеса | elprof.ru |

**Проблема:** Токен создан для личного аккаунта или другой организации, а форма — в организации `2188002634`.

---

## Решения

### Вариант 1: Получить токен от организации формы (рекомендуется)

1. Зайти в [Яндекс ID](https://oauth.yandex.ru/) под аккаунтом создателя формы
2. Создать OAuth-приложение с правами `forms:write` и `forms:read`
3. Получить новый токен
4. Обновить `YANDEX_OAUTH_TOKEN` в `.env`

```bash
# На сервере:
nano .env
# Заменить YANDEX_OAUTH_TOKEN=новый_токен
php artisan config:clear
```

### Вариант 2: Создать форму в организации токена

1. Зайти в [Яндекс Формы](https://forms.yandex.ru/) под `pavel.a.klimov@elprof.ru`
2. Создать новую копию формы
3. Скопировать все вопросы из старой формы
4. Опубликовать новую форму
5. Обновить `yandex_form_id` в `form_templates`

```php
// Обновить form_template в БД
php artisan tinker --execute="
\App\Models\FormTemplate::where('id', 1)->update([
    'yandex_form_id' => 'новый_id_формы'
]);
"
```

### Вариант 3: Добавить пользователя в организацию формы

1. В панели Яндекс 360 для бизнеса (администратор организации `2188002634`)
2. Добавить `pavel.a.klimov@elprof.ru` как сотрудника
3. Выдать права на форму

---

## Как проверить после исправления

```php
// В tinker:
$api = app(\App\Services\YandexFormsApi::class);
$answer = $api->getAnswer('6a46534d90290237129cb245', '2466704902');

if ($answer) {
    echo '✅ API работает!' . PHP_EOL;
    echo 'Данные: ' . json_encode($answer['data'], JSON_UNESCAPED_UNICODE) . PHP_EOL;
} else {
    echo '❌ API всё ещё не работает' . PHP_EOL;
}
```

---

## Связанные файлы

| Файл | Проблема |
|------|----------|
| `app/Services/YandexFormsApi.php` | Неверный эндпоинт и заголовок в `getAnswer()`/`getAnswers()` |
| `app/Filament/Resources/AnonParticipants/Pages/EditAnonParticipant.php` | Загружает данные из API, показывает ошибки |
| `app/Jobs/ExportAnonParticipantsWithPdJob.php` | Экспорт не работает без доступа к API |
| `.env` | `YANDEX_OAUTH_TOKEN` и `YANDEX_ORG_ID` |

---

## Исправление API (после получения правильного токена)

```php
// app/Services/YandexFormsApi.php

// Заголовки — заменить X-Cloud-Org-Id на X-Org-Id
private function headers(): array
{
    $headers = ['Authorization' => 'OAuth ' . $this->token];
    if ($this->orgId) {
        $headers['X-Org-Id'] = $this->orgId;  // Было: X-Cloud-Org-Id
    }
    return $headers;
}

// getAnswer — исправить эндпоинт
public function getAnswer(string $formId, string $answerId): ?array
{
    $response = Http::withHeaders($this->headers())
        ->timeout(30)
        ->get("{$this->baseUrl}/answers", [  // Было: /surveys/{formId}/answers/{answerId}
            'answer_id' => $answerId,         // Как query parameter
        ]);
    // ...
}

// getAnswers — эндпоинт уже правильный, нужен только заголовок
public function getAnswers(string $formId, array $filters = []): array
{
    $response = Http::withHeaders($this->headers())  // headers() уже должен содержать X-Org-Id
        ->timeout(30)
        ->get("{$this->baseUrl}/surveys/{$formId}/answers", [
            'page_size' => 100,
        ]);
    // ...
}
```
