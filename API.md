# API v1 — документация

Базовый URL: `{APP_URL}/api/v1`
Формат: `application/json` (`Accept: application/json`), авторизация — Bearer-токен Sanctum.
Интерактивная документация (Swagger UI): `GET /docs/api` → `public/openapi.yaml`.

## Аутентификация

Защищённые эндпоинты требуют заголовок:

```
Authorization: Bearer <token>
Accept: application/json
```

### POST /auth/login
Вход по email и паролю. Rate limit: 6 запросов/мин.

```bash
curl -X POST "$BASE/auth/login" \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"secret123","device_name":"android"}'
```

Ответ `200`:

```json
{
  "data": {
    "token": "1|abcdef...",
    "token_type": "Bearer",
    "user": { "id": 1, "name": "Иван", "email": "user@example.com", "roles": ["admin"], "created_at": "2026-10-09T10:00:00+00:00" }
  }
}
```

Ошибки: `422` — неверные учётные данные (в теле `errors.email`).

### POST /auth/logout  🔒
Отзывает текущий токен.

```bash
curl -X POST "$BASE/auth/logout" -H "Authorization: Bearer $TOKEN" -H "Accept: application/json"
```
Ответ `200`: `{"message":"Logged out"}`

### GET /me  🔒
Профиль текущего пользователя.

```json
{ "data": { "id": 1, "name": "Иван", "email": "user@example.com", "roles": ["admin"], "created_at": "..." } }
```

## Публичные эндпоинты

### GET /events
Список мероприятий (пагинация Laravel: `data`, `links`, `meta`).

Query-параметры:
| Параметр | Тип | Описание |
|---|---|---|
| `status` | string | Фильтр по статусу (`published`, `active`, `completed`, ...) |
| `upcoming` | bool | Только предстоящие |
| `active` | bool | Только активные |
| `per_page` | int | Элементов на страницу (по умолчанию 15) |

Без параметров возвращаются только `published`.

```bash
curl "$BASE/events?status=published&per_page=10" -H "Accept: application/json"
```

### GET /events/{slug}
Мероприятие по slug с вложенными `days`, `speakers`, `guests`, `testimonials`, `faqs`, `documents`. `404`, если не найдено.

### GET /events/{slug}/schedule
Расписание по дням. `data[]` — дни (`date`, `label`, `events[]` со `start_time`, `end_time`, `title`, `speaker`, `is_break`).

### GET /events/{slug}/speakers
Спикеры мероприятия.

### GET /events/{slug}/guests
Гости мероприятия.

### GET /events/{slug}/faq
FAQ мероприятия (`question`, `answer`).

### GET /events/{slug}/documents
Документы (`title`, `file_path`, `file_type`).

### GET /events/{slug}/gallery
Галерея мероприятия:

```json
{
  "data": {
    "slug": "event-slug",
    "gallery": ["http://.../storage/gallery/1.jpg"],
    "media_image": null,
    "media_description": null,
    "is_media_visible": false,
    "external": {
      "url": "https://disk.example.com/...",
      "description": "<p>...</p>",
      "is_visible": true,
      "has_external_gallery": true,
      "qr_url": "http://.../qr/gallery/event-slug"
    }
  }
}
```

### GET /testimonials
Глобальный список активных отзывов.

## Билеты и чек-ин

`token` здесь — это `checkin_token` участника, он же секрет доступа к билету.

### GET /ticket/{token}
Данные билета (публично, token = секрет).

```json
{
  "data": {
    "type": "classic",
    "token": "abc...",
    "status": "registered",
    "is_checked_in": false,
    "checked_in_at": null,
    "participant": { "id": 10, "name": "Иван", "email": "user@example.com" },
    "event": { "slug": "event-slug", "title": "...", "start_date": "2026-10-10", "end_date": "2026-10-11", "venue_name": "...", "venue_address": "...", "poster_image": null },
    "checkin_url": "http://.../checkin/abc...",
    "qr_url": "http://.../api/v1/ticket/abc.../qr",
    "ticket_url": "http://.../ticket/abc..."
  }
}
```
`type`: `classic` (участник) или `anon`. `attributes` присутствует только для `anon`. `404`, если токен неизвестен.

### GET /ticket/{token}/qr
PNG QR-кода (400×400), ведущий на веб-чек-ин. `Content-Type: image/png`.

### POST /checkin/by-token  🔒
Отметить участника по токену. Идемпотентно: повторный вызов не меняет время отметки.

```bash
curl -X POST "$BASE/checkin/by-token" \
  -H "Authorization: Bearer $TOKEN" -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"token":"abc..."}'
```

Ответ `200` — та же структура, что у билета, плюс `already_checked_in`:

```json
{ "data": { "type": "classic", "is_checked_in": true, "already_checked_in": false, "status": "arrived", "checked_in_at": "2026-10-10T09:00:00+00:00", "...": "..." } }
```

Ошибки: `401` (нет токена), `404` (неизвестный токен), `422` (не передан `token`).

## Ошибки

```json
{ "message": "Not Found" }
```
- `401` — нет/истёк токен;
- `404` — ресурс не найден;
- `422` — ошибка валидации (`message` + `errors`);
- `429` — превышен rate limit (login).

## Коды в тестах
Покрытие: `tests/Feature/Api/V1` (`EventsApiTest`, `AuthApiTest`, `TicketApiTest`, `CheckinApiTest`).

```bash
php artisan test tests/Feature/Api/V1
```
