# Детальный план: Мобильное Android-приложение (Flutter + Laravel API)

## Цель
Создать Android-приложение поверх существующего проекта (Laravel 13 + Filament v5.6.1). Админка остаётся источником правды; API — тонкая обёртка над теми же моделями.

## Текущее состояние (кратко)
- Laravel 13, PHP 8.4, Filament 5.6.1, Livewire 4.2.4
- Модели: Events, EventDays, ScheduleEvent, Speakers, Guests, Testimonials, Faqs, EventDocuments, Participants, AnonParticipants, Newsletters, EmailTemplates, FormTemplates, DocumentTemplates, Exports, Roles/Users (Shield)
- API сейчас: POST /api/yandex/register, POST /api/gallery-view
- Sanctum установлен, HasApiTokens в User, config/cors.php готов
- flutter_app/ пустой

## Этап 0. Анализ и приоритизация
- [ ] Уточнить стек: Flutter (рекомендуется) или Kotlin нативный
- [ ] Решить: только read-only для посетителей + чек-ин, или ещё личный кабинет участника
- [ ] Определить минимальный MVP (список событий, расписание, спикеры, галерея, FAQ, документы) vs расширенный

## Этап 1. Подготовка окружения (потом)
- [ ] Установить Flutter SDK (stable)
- [ ] Установить Android Studio (или настроить Android SDK + VS Code)
- [ ] Настроить эмулятор Android / физическое устройство
- [ ] Проверить flutter doctor

## Этап 2. Laravel API (реализовать в этом проекте)
### 2.1 Sanctum + конфиг
- [ ] Убедиться в bootstrap/app.php наличие api routing (уже есть)
- [ ] Добавить middleware для api (throttle, sanctum auth где нужно)
- [ ] Подтвердить config/sanctum.php

### 2.2 API Resources (DTO)
- [ ] EventResource (id, slug, title, dates, venue, poster, logo, registration fields, etc)
- [ ] EventDayResource + ScheduleEventResource (расписание по дням)
- [ ] SpeakerResource, GuestResource, TestimonialResource, FaqResource
- [ ] EventDocumentResource, HeroSlideResource (при необходимости)
- [ ] ParticipantResource (минимум для авторизованного)

### 2.3 Публичные эндпоинты (v1)
- [ ] GET /api/v1/events?status=published&upcoming=1&active=1
- [ ] GET /api/v1/events/{slug}
- [ ] GET /api/v1/events/{slug}/schedule
- [ ] GET /api/v1/events/{slug}/speakers
- [ ] GET /api/v1/events/{slug}/guests
- [ ] GET /api/v1/events/{slug}/faq
- [ ] GET /api/v1/events/{slug}/documents
- [ ] GET /api/v1/events/{slug}/gallery
- [ ] GET /api/v1/testimonials

### 2.4 Авторизация (минимум)
- [ ] POST /api/v1/auth/login (email+password) -> token
- [ ] POST /api/v1/auth/logout (revoke token)
- [ ] GET /api/v1/me (профиль)

### 2.5 Защищённые/полезные
- [ ] GET /api/v1/ticket/{token}/qr (по токену участника)
- [ ] POST /api/v1/checkin/by-token (чек-ин через приложение, если есть права)
- [ ] POST /api/v1/registration/anon (если планируем мобильную регистрацию помимо Яндекс)

### 2.6 Качество API
- [ ] Form Requests для валидации
- [ ] API Resources с явными полями (исключить лишнее)
- [ ] Rate limiting (throttle:api)
- [ ] Eager loading там где нужно
- [ ] Обработка 404/422 в JSON для api/*
- [ ] Тесты Feature (минимум 3–5)

## Этап 3. Flutter-приложение
### 3.1 Инициализация
- [ ] Создать flutter_app/events_mobile (или в flutter_app/)
- [ ] Настроить pubspec.yaml (dio, flutter_riverpod/riverpod, go_router, freezed/json_serializable, flutter_secure_storage, url_launcher, cached_network_image)
- [ ] Конфиг .env / flutter_dotenv для API_BASE_URL

### 3.2 Архитектура
- [ ] data/datasources/remote (API client + interceptors)
- [ ] data/models (DTO, freezed)
- [ ] data/repositories
- [ ] domain/entities/usecases (опц.)
- [ ] presentation/screens (home, event_detail, schedule, speakers...)
- [ ] presentation/widgets + theme
- [ ] core/constants, core/network, core/router

### 3.3 Экраны MVP
- [ ] Главная / Список событий
- [ ] Деталка события (описание, даты, место)
- [ ] Расписание по дням (табы)
- [ ] Спикеры/Гости
- [ ] Галерея (светлая/мозаика + lightbox)
- [ ] FAQ
- [ ] Документы
- [ ] Билет/QR (при авторизации)

### 3.4 Интеграция
- [ ] Подключение к Laravel API
- [ ] Обработка состояний (loading/error/empty)
- [ ] Pull-to-refresh
- [ ] Кэш изображений
- [ ] Deep links (опц.)

## Этап 4. Тестирование и сборка
- [ ] Ручной тест на эмуляторе
- [ ] Тест API через Postman/curl
- [ ] Сборка debug APK
- [ ] (опц.) release AAB для Google Play

## Этап 5. Документация
- [ ] API.md (эндпоинты + примеры)
- [ ] flutter_app/README.md (как запустить)
- [ ] Обновить ADMIN_ANALYSIS.md при изменениях

## Приоритет MVP
1. Laravel: v1/public endpoints (events + schedule + speakers + faq + docs + gallery)
2. Flutter: список событий + деталка + расписание
3. Auth + билет только при реальной потребности

## Файлы для создания/правки (Laravel)
- routes/api.php — добавить v1 группы
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


