# Детальный план: Мобильное Android-приложение (Flutter + Laravel API)

## Цель
Создать Android-приложение поверх существующего проекта (Laravel 13 + Filament v5.6.1). Админка остаётся источником правды; API — тонкая обёртка над теми же моделями.

## Текущее состояние (кратко)
- Laravel 13, PHP 8.4, Filament 5.6.1, Livewire 4.2.4
- Модели: Events, EventDays, ScheduleEvent, Speakers, Guests, Testimonials, Faqs, EventDocuments, Participants, AnonParticipants, Newsletters, EmailTemplates, FormTemplates, DocumentTemplates, Exports, Roles/Users (Shield)
- API v1: 15 эндпоинтов (events, sub-resources, testimonials, auth, ticket, checkin) — см. `API.md`
- OpenAPI-спек: `public/openapi.yaml`, Swagger UI: `GET /docs/api`
- Sanctum установлен, HasApiTokens в User, config/cors.php готов
- Тесты: 26 Feature-тестов API v1 (`tests/Feature/Api/V1`)
- flutter_app/events_mobile: каркас готов (Flutter 3.47.7, Riverpod/Dio/GoRouter/Freezed; сборка web проходит)

## Этап 0. Анализ и приоритизация
- [x] Уточнить стек: **Flutter** (Riverpod + Dio + GoRouter + Freezed)
- [x] Решить: read-only для посетителей + чек-ин + билет при авторизации; **личный кабинет не нужен**, анонимная регистрация не нужна (только участники мероприятия)
- [x] Определить минимальный MVP (список событий, расписание, спикеры, галерея, FAQ, документы)

## Этап 1. Подготовка окружения
- [x] Установить Flutter SDK (stable) — 3.47.7
- [ ] Установить Android Studio (в процессе у пользователя)
- [ ] Настроить эмулятор Android / физическое устройство
- [x] flutter doctor (запущен; ожидается Android SDK)

## Этап 2. Laravel API (реализовать в этом проекте)
### 2.1 Sanctum + конфиг
- [x] Убедиться в bootstrap/app.php наличие api routing (уже есть)
- [x] Добавить middleware для api (throttle, sanctum auth где нужно)
- [x] Подтвердить config/sanctum.php

### 2.2 API Resources (DTO)
- [x] EventResource (id, slug, title, dates, venue, poster, logo, registration fields, etc)
- [x] EventDayResource + ScheduleEventResource (расписание по дням)
- [x] SpeakerResource, GuestResource, TestimonialResource, FaqResource
- [x] EventDocumentResource, GalleryResource
- [ ] ParticipantResource (не требуется — участник отдаётся внутри TicketResource)

### 2.3 Публичные эндпоинты (v1)
- [x] GET /api/v1/events?status=published&upcoming=1&active=1
- [x] GET /api/v1/events/{slug}
- [x] GET /api/v1/events/{slug}/schedule
- [x] GET /api/v1/events/{slug}/speakers
- [x] GET /api/v1/events/{slug}/guests
- [x] GET /api/v1/events/{slug}/faq
- [x] GET /api/v1/events/{slug}/documents
- [x] GET /api/v1/events/{slug}/gallery
- [x] GET /api/v1/testimonials

### 2.4 Авторизация (минимум)
- [x] POST /api/v1/auth/login (email+password) -> token
- [x] POST /api/v1/auth/logout (revoke token)
- [x] GET /api/v1/me (профиль)

### 2.5 Защищённые/полезные
- [x] GET /api/v1/ticket/{token}
- [x] GET /api/v1/ticket/{token}/qr (по токену участника)
- [x] POST /api/v1/checkin/by-token (чек-ин, Sanctum, идемпотентно)
- [~] POST /api/v1/registration/anon — **не требуется** (регистрация только участников мероприятия)

### 2.6 Качество API
- [x] Form Requests для валидации
- [x] API Resources с явными полями (исключить лишнее)
- [~] Rate limiting: `throttle:6,1` на login; глобальный `throttle:api` не выставлен
- [x] Eager loading там где нужно
- [x] Обработка 404/422 в JSON для api/*
- [x] Тесты Feature (26 тестов, все зелёные)

## Этап 3. Flutter-приложение
### 3.1 Инициализация
- [x] Создать flutter_app/events_mobile (org ru.eventapp, platforms android/ios/web)
- [x] Настроить pubspec.yaml (dio, flutter_riverpod, go_router, freezed/json_serializable, flutter_secure_storage, url_launcher, cached_network_image, intl, flutter_dotenv)
- [x] Конфиг .env / flutter_dotenv для API_BASE_URL

### 3.2 Архитектура
- [x] data/datasources/remote (ApiClient + auth interceptor)
- [x] data/models (DTO, freezed)
- [x] data/repositories (events, auth, ticket)
- [ ] domain/entities/usecases (опц.)
- [x] presentation/screens (home, event_detail, login, ticket)
- [x] presentation/widgets + theme (app_theme, app_colors, remote_image, event_card, async_value_view)
- [x] core/constants, core/network, core/router

### 3.3 Экраны MVP
- [x] Главная / Список событий
- [x] Деталка события (описание, даты, место)
- [x] Расписание по дням (таб)
- [x] Спикеры/Гости (таб)
- [x] Галерея (таб, сетка)
- [x] FAQ (таб)
- [x] Документы (таб)
- [x] Билет/QR + вход сотрудника

### 3.4 Интеграция
- [x] Подключение к Laravel API
- [x] Обработка состояний (loading/error/empty)
- [x] Pull-to-refresh
- [x] Кэш изображений
- [ ] Deep links (опц.)

## Этап 4. Тестирование и сборка
- [ ] Ручной тест на эмуляторе
- [ ] Тест API через Postman/curl
- [ ] Сборка debug APK
- [ ] (опц.) release AAB для Google Play

## Этап 5. Документация
- [x] API.md (эндпоинты + примеры)
- [x] public/openapi.yaml + Swagger UI (/docs/api)
- [x] flutter_app/README.md (как запустить)
- [ ] Обновить ADMIN_ANALYSIS.md при изменениях

## Приоритет MVP
1. Laravel: v1/public endpoints (events + schedule + speakers + faq + docs + gallery) — **готово**
2. Flutter: список событий + деталка + расписание
3. Auth + билет только при реальной потребности

## Файлы для создания/правки (Laravel)
- routes/api.php — v1-группа готова
- app/Http/Controllers/Api/V1/* (EventsController, ScheduleController...)
- app/Http/Resources/Api/V1/*
- app/Http/Requests/Api/V1/*
- config/cors.php (уже есть)
- tests/Feature/Api/V1/*

## Заметки
- Не дублировать бизнес-логику: использовать модели + scopes
- Возвращать только нужные поля через API Resources
- Для галереи и медиа — использовать Storage::url
- clean_html уже есть (при показе RichEditor)
- Сохранять план в MOBILE_APP_PLAN.md
