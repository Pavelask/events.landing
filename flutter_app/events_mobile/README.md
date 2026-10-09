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
   - Android-эмулятор: замените хост на LAN IP машины, напр. `http://192.168.1.10/api/v1` (или настройте `adb reverse`).
2. Установите зависимости и сгенерируйте код:
   ```bash
   flutter pub get
   dart run build_runner build
   ```

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
