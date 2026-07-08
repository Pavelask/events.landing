# Changelog: Система регистрации на мероприятия

## Дата: 8 июля 2026

---

## 1. Регистрация через Яндекс Формы (API)

### Проблема
Форма `/events/.../register-anon` возвращала 404 из-за проверки `registration_type !== 'yandex_api'`.

### Решение
- Проверка корректна — `registration_type` в БД содержит `yandex_api`
- Форма работает, данные отправляются в Яндекс Форму через API

### Файлы
- `app/Livewire/AnonRegistration.php`
- `resources/views/livewire/anon-registration.blade.php`

---

## 2. Удаление hCaptcha

### Проблема
hCaptcha интегрирована неполно (нет JS для рендеринга виджета).

### Решение
Удалена полностью из 4 файлов:
- `resources/views/livewire/anon-registration.blade.php` — блок виджета
- `config/services.php` — ключ `hcaptcha`
- `.env.example` — переменные `HCAPTCHA_SITE_KEY`, `HCAPTCHA_SECRET_KEY`
- `.env` — реальные ключи

---

## 3. Валидация полей формы

### Клиентская (Alpine.js)
- **ФИО**: обязательное поле, красная подсветка при пустом значении
- **Email**: обязательное + проверка формата
- **Телефон**: маска `+7 (999) 123-45-67`, авто-замена `8` → `+7`, очистка неполного при уходе с поля

### Серверная (Livewire)
- ФИО: обязательно
- Email: обязательно + формат
- Телефон: если заполнен — 11 цифр
- Динамические вопросы: `required` + формат даты + select options

### Фокус на ошибку
- `data-err` атрибут на всех ошибочных полях
- `Livewire.hook('morph.updated')` + `requestAnimationFrame` + fallback 100мс
- `TreeWalker` для обхода DOM в порядке шаблона

---

## 4. Интеграция с API Яндекс Форм

### Токен и организация
- **Токен**: `y0__wgBEMqKqZMIGOWNRSD_jKmaGDDZ46q_CGp9pJhwuN5hc44j6I6PiMU2Bw0H`
- **Org ID**: `3424993`
- **Форма**: `6a46534d90290237129cb245` (старая, рабочая)
- **Владелец**: `vep.elprof` (организация)

### API эндпоинты (исправлено)
- **Запись**: `POST /v1/surveys/{formId}/form` — публичный, работает
- **Чтение**: `GET /v1/answers?answer_id={id}` с заголовком `X-Org-Id` (не `X-Cloud-Org-Id`)
- **Список**: `GET /v1/surveys/{formId}/answers` с заголовком `X-Org-Id`

### Структура данных ответа
```json
{
  "id": 12345,
  "data": [
    {"id": "event_id", "label": "Скрытое поле для event_id", "value": "1"},
    {"id": "name", "label": "ФИО участника", "value": "..."},
    {"id": "email", "label": "Почта", "value": "..."},
    {"id": "phone", "label": "Телефон", "value": "..."}
  ]
}
```

### Маппинг полей
- `ФИО участника` → `yandex_name`
- `Почта` → `yandex_email`
- `Телефон` → `yandex_phone`
- Кастомные вопросы: `label` → `custom_{slug}`

### Важно
- `strtolower()` НЕ работает с кириллицей — используется `mb_strtolower()`
- `getAnswers` (список) возвращает данные без лейблов — только `[{value: ...}]`
- `getAnswer` (одиночный) возвращает данные с лейблами — `[{id, label, value}]`

### Файлы
- `app/Services/YandexFormsApi.php`
- `YANDEX_ORG_ISSUE.md` — документация проблемы
- `app/Console/Commands/TestYandexApi.php` — тест API

---

## 5. Таблица участников (FilamentPHP)

### Колонки
- `#` (ID) — сортировка
- `Мероприятие` — название события
- `Статус` — бейдж (Зарег./Прибыл/Отменён)
- `Регистрация` — дата и время
- `Чек-ин` — дата и время
- `Билет` — иконка (отправлен/не отправлен)

### Разметка
- `Split` layout: 3 группы (инфо, даты, билет) — от `lg`
- `StackedOnMobile` — карточки на мобильных
- Все поля на одном экране

### Фильтры
- Мероприятие (SelectFilter с relationship)
- Статус (SelectFilter)

### Действия
- **Отправить билет** — получает email из Яндекс API, отправляет TicketMail
- **Отметить прибывших** — устанавливает `checked_in_at` и статус `arrived`
- **Сбросить чек-ин** — очищает `checked_in_at`, статус → `registered`
- **Отменить регистрацию** — статус → `cancelled`
- **Редактирование** — переход на страницу `/edit`

### Массовые действия
- Экспорт (CSV)
- Отправить билеты (с ошибками по участникам)
- Отметить прибывших
- Экспорт с ПД (async job)

### Файлы
- `app/Filament/Resources/AnonParticipants/Tables/AnonParticipantsTable.php`
- `app/Filament/Resources/AnonParticipants/AnonParticipantResource.php`

---

## 6. Страница редактирования участника

### Секции
1. **Основная информация** — Мероприятие (full width), Статус + Answer ID (2 колонки)
2. **Данные из Яндекс Формы** — ФИО, Email, Телефон + кастомные вопросы (read-only, загружаются из API)
3. **Чек-ин и билеты** — Время чек-ина, Билет отправлен, Checkin Token
4. **Отметки о выдаче** — Сувенир, Документация, Одежда (тоглы)

### Загрузка данных
- Данные загружаются из API Яндекс Форм при открытии страницы
- Если `answer_id` начинается с `LOCAL_` — показывается предупреждение
- Если API возвращает ошибку — показывается уведомление с деталями

### Файлы
- `app/Filament/Resources/AnonParticipants/Pages/EditAnonParticipant.php`
- `app/Filament/Resources/AnonParticipants/Schemas/AnonParticipantForm.php`

---

## 7. Email уведомления

### Регистрация
- Письмо после успешной регистрации
- Тема: «Регистрация подтверждена: {название мероприятия}»
- Содержание: поздравление, название, номер регистрации

### Билет
- `TicketMail` принимает `Participant|AnonParticipant`
- Ссылка на билет: `/ticket/{checkin_token}`

### Файлы
- `app/Mail/RegistrationConfirmationMail.php`
- `app/Mail/TicketMail.php`
- `resources/views/emails/registration-confirmation.blade.php`

---

## 8. Экспорт данных с ПД

### Async Job
- `ExportAnonParticipantsWithPdJob` — асинхронная задача
- Загружает данные из API Яндекс Форм для каждого участника
- Генерирует Excel файл
- Уведомление администратору с ошибками

### Ошибки
- `ID #X: нет form_id` — у мероприятия нет формы
- `ID #X: локальный ответ` — данные не в Яндексе
- `ID #X: ошибка API` — не удалось получить данные

### Файлы
- `app/Jobs/ExportAnonParticipantsWithPdJob.php`

---

## 9. Импорт из Яндекс Форм

### Формат данных
- `getAnswers` возвращает `[{value: ...}]` без лейблов
- `event_id` извлекается из позиции 0 массива

### Дедупликация
- Проверка по `answer_id` — пропуск существующих записей

### Файлы
- `app/Filament/Resources/AnonParticipants/Tables/AnonParticipantsTable.php` (headerAction)

---

## 10. Тестирование API

### Команда
```bash
php artisan app:test-yandex-api
```

### Что проверяет
1. Конфигурация (токен, org_id, form_id)
2. Владелец токена
3. Запись ответа (createAnswer)
4. Чтение ответа (getAnswer)
5. Список ответов (getAnswers)

### Файлы
- `app/Console/Commands/TestYandexApi.php`

---

## 11. Исправленные баги

| Баг | Причина | Решение |
|-----|---------|---------|
| `mb_mb_strtolower()` | Двойная замена sed | Исправлено на `mb_strtolower()` |
| `TicketMail` type error | Type hint `Participant` | Union type `Participant\|AnonParticipant` |
| Нет фокуса на ФИО/Email | TreeWalker не искал input/select | `requestAnimationFrame` + fallback 100мс |
| Двойные сообщения ошибок | Серверная + клиентская одновременно | Убрана серверная для динамических вопросов |
| Фильтр скрывал записи | `ticket_sent` без placeholder | Удалён фильтр |
| Телефон невалиден в API | Скобки/пробелы в номере | Нормализация перед отправкой |
| Checkbox = boolean | Livewire отправляет `true` | Конвертация в строку `'Да'`/`'Нет'` |

---

## 12. Зависимости

| Пакет | Версия | Использование |
|-------|--------|---------------|
| filament/filament | v5.6.1 | Админ-панель |
| maatwebsite/excel | ^3.1 | Excel экспорт |
| barryvdh/laravel-dompdf | ^3.1 | PDF билеты |
| simplesoftwareio/simple-qrcode | * | QR коды |
| spatie/laravel-sluggable | * | Авто-slug |
