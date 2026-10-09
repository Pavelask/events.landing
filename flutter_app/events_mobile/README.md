# events_mobile

Android/Flutter приложение для просмотра мероприятий и чек-ина участников. Работает поверх Laravel API v1 (`/api/v1`).

## Стек
Flutter 3.47+, Riverpod, Dio, GoRouter, Freezed/json_serializable, flutter_secure_storage, cached_network_image, url_launcher, intl, flutter_dotenv.

## Требования
- Flutter SDK (stable)
- Для Android: Android Studio + Android SDK (эмулятор или устройство)

## Настройка
1. Скопируйте `.env.example` в `.env` и укажите базовый URL API:
   - Web/macOS (Herd): `API_BASE_URL=http://landing.test/api/v1`
2. Установите зависимости и сгенерируйте код:
   ```bash
   flutter pub get
   dart run build_runner build
   ```

## Android-эмулятор
Herd-домен `*.test` не резолвится из эмулятора, поэтому для него API поднимают
отдельно и хост подставляется автоматически:
```bash
# в корне Laravel-проекта
php artisan serve --host=0.0.0.0 --port=8080
```
В `Env` для Android адрес `landing.test/localhost/127.0.0.1` автоматически
переписывается на `http://10.0.2.2:8080`. Медиа-ссылки (относительные
`/storage/...` и абсолютные) нормализуются к тому же origin через
`lib/core/utils/url_utils.dart`.

Ручной запуск на конкретном устройстве:
```bash
flutter run -d emulator-5554
```
Адрес можно переопределить: `flutter run --dart-define=API_BASE_URL=http://10.0.2.2:8080/api/v1`.

## Запуск
```bash
flutter run                 # выбрать устройство
flutter run -d chrome       # web
flutter run -d macos        # macOS desktop
```

## Проверки
```bash
flutter analyze
flutter test
```

## Структура
```
lib/
  core/          config, di (riverpod providers), network (dio), router, storage, theme, utils
  data/          models (freezed DTO), repositories
  presentation/  providers, screens, widgets
```

## Эндпоинты (используемые)
- `GET /events`, `GET /events/{slug}` (с days/speakers/guests/faqs/documents)
- `GET /ticket/{token}`, `GET /ticket/{token}/qr`
- `POST /auth/login`, `POST /auth/logout`, `GET /me`
- `POST /checkin/by-token` (требует авторизации)

Полное описание — в корневом `API.md` и `public/openapi.yaml`.
