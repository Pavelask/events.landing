# Лог работы: Система регистрации на мероприятия (обезличенное хранение)

## Дата начала: 6 июля 2026

---

## 1. Создание инфраструктуры

### Миграции (созданы):
- `2026_07_06_100000_create_form_templates_table.php` — шаблоны форм
- `2026_07_06_100001_create_anon_participants_table.php` — обезличенные участники
- `2026_07_06_100002_create_newsletters_table.php` — история рассылок (таблица уже существовала)
- `2026_07_06_100003_add_form_template_id_to_events_table.php` — FK form_template_id в events
- `2026_07_06_100004_add_yandex_form_id_to_form_templates_table.php` — yandex_form_id в form_templates

### Модели (созданы):
- `FormTemplate` — name, yandex_form_id, questions (JSON)
- `AnonParticipant` — event_id, answer_id, checkin_token, status, checked_in_at, ticket_sent_at, souvenir_given, documentation_given, clothing_given

### Обновления существующих моделей:
- `Event` — добавлены связи `formTemplate()` и `anonParticipants()`

---

## 2. Сервисы

### YandexFormsApi — обновлён:
- Добавлен метод `findAnswersByEmail()`
- Исправлен формат авторизации: `Authorization: OAuth <token>` (не Bearer)
- Исправлен endpoint: `POST /surveys/{formId}/form` (не `/forms/{formId}/answers`)
- Добавлен заголовок `X-Cloud-Org-Id` для организации

### AntiBotService — создан:
- Honeypot (ловушка для ботов)
- Time-check (проверка времени заполнения)
- Math-question (математический вопрос)

---

## 3. Filament ресурсы

### FormTemplateResource (создан):
- Конструктор шаблонов форм (name, yandex_form_id, questions)
- Repeater для вопросов с drag-and-drop
- Типы полей: text, textarea, select, radio, checkbox, date
- Поиск в select (searchable)

### AnonParticipantResource (создан):
- Таблица участников с фильтрами
- Действия: отправка билетов, чек-ин, отмена регистрации
- Массовые действия: экспорт, импорт из Яндекс Форм

### EventResource — обновлён:
- Добавлен выбор шаблона формы (form_template_id)
- Добавлена опция `yandex_api` в тип регистрации

---

## 4. Публичная форма регистрации

### AnonRegistration (Livewire компонент):
- Загрузка мероприятия и шаблона формы
- Динамическая генерация полей на основе questions
- Валидация обязательных полей
- Отправка данных через API Яндекс Форм
- Создание записи в anon_participants

### Blade шаблон:
- Динамический рендеринг: text, textarea, select, radio, checkbox, date
- Маска ввода для даты (ДД.ММ.ГГГГ)
- Поиск в выпадающих списках (searchable select)
- Валидация: красный label, border, сообщение об ошибке

---

## 5. Интеграция с API Яндекс Форм

### Проблемы и решения:
1. **401 Unauthorized** — использовался `Bearer` вместо `OAuth` в заголовке авторизации
2. **404 Not Found** — неправильный endpoint (`/forms/{id}/answers` вместо `/surveys/{id}/form`)
3. **Требуется организация** — нужен заголовок `X-Cloud-Org-Id` с organization ID
4. **Формат данных** — нужно отправлять JSON через `withBody(json_encode($data), 'application/json')`

### Рабочий формат запроса:
```
POST https://api.forms.yandex.net/v1/surveys/{formId}/form
Headers:
  Authorization: OAuth {token}
  X-Cloud-Org-Id: {orgId}
  Content-Type: application/json
Body: { "event_id": "1", "name": "...", "email": "...", "custom_1": "..." }
```

---

## 6. Проблемы с Livewire и валидацией

### Проблема: форма отправлялась как обычный HTML (GET запрос)
**Причина:** атрибуты `name` на полях формы (honeypot, radio, checkbox) заставляли браузер отправлять форму как обычный HTML.
**Решение:** убраны все атрибуты `name` с полей формы.

### Проблема: валидация не отображалась
**Причина:** Livewire не перехватывал отправку формы, Alpine.js не работал.
**Решение:** откат к исходной форме, работоспособность подтверждена.

---

## 7. Конфигурация сервера

### .env переменные:
- `YANDEX_OAUTH_TOKEN` — токен Яндекс OAuth
- `YANDEX_ORG_ID` — ID организации (elprof.ru)

### Organization ID:
- Получен из `login.yandex.ru/info` → user ID: `2188002634`
- Используется как `X-Cloud-Org-Id` в заголовках API

---

## 8. Финальное состояние

### Рабочие компоненты:
- Форма регистрации (Livewire + Blade) — работает
- API Яндекс Форм — работает (createAnswer возвращает answer_id)
- Filament ресурсы (FormTemplate, AnonParticipant) — работают
- Миграции выполнены на сервере

### Известные ограничения:
- OAuth токен нужно обновлять вручную
- Questions в form_template заполняются через админку
- Валидация работает через Livewire (не через Alpine.js)

---

## Коммиты:
- `510905e` — feat: add anonymized registration system with Yandex Forms API
- `ffe7076` — feat: add Yandex Forms API with org_id, fallback to local save
- `badd71e` — fix: rebuild assets, add Yandex Forms API with org_id
- `27cc382` — fix: publish Livewire config
- `19c09e7` — test: add server test script for registration system
- `bd300bd` — fix: restore complete form, fix truncated Blade file

---

## �СЕССИЯ 2026-09-17: починка отправки писем + доступ к серверу

### Контекст
- Проект: events.elprof.ru (Laravel, локальная копия /Users/pavelklimov/Herd/landing).
- Письма участникам шли через очередь (QUEUE_CONNECTION=database, TemplateMail implements ShouldQueue), но воркер не был настроен — задания копились в jobs, доставки не было.

### Что сделано (коммиты)
- 475a13e — feat: индивидуальная отправка писем (тест на себя + по шаблону каждому участнику).
- 2d12e93 — fix: save-and-close крашился на redirect (getLivewire()->redirect() на null).
- 9085455 — fix: filament:assets падал на null-path html/Css ассете.
- c190e03 — docs: supervisor-конфиг воркера + README + launchd-скрипт (deploy/supervisor/).

### Проверка SMTP (работает)
- nc smtp.yandex.ru 587/465 — OK (порт открыт).
- Живой тест через tinker: TemplateMail реально уходит (SEND OK) на pavelask@mail.ru (тест на себя) и pavel.a.klimov@elprof.ru (синхронно, в обход очереди).

### Проблемы, найденные в логах
- failed_jobs: записи с recipient id=0 (тест «на себя», ModelIdentifier не находит модель) — такие retry бессмысленны, чистить, а не ретраить.
- Ранее в local-логе: Table not found email_templates/form_templates/notifications (ситуация после миграций), Connection refused mysql (старое).

### Сервер события (сервер, Rocky Linux)
- Сайт жив: curl -sI https://events.elprof.ru → HTTP/1.1 200 OK (443 отвечает, БД доступна).
- SSH(22): connection refused — sshd/фаервол/fail2ban. Физический сервер, консоли/панели нет, провайдер неизвестен.
- migrate:status на сервере: все миграции Ran (email_templates, form_templates, participants, jobs, failed_jobs есть в БД).

### Что нужно сделать, когда вернётся доступ (SSH/консоль)
1. php artisan migrate --force
2. php artisan queue:work --queue=default,sendnewsletter  (или supervisor-конфиг из deploy/supervisor/)
3. php artisan queue:retry all (предварительно почистить failed_jobs с recipient id=0)
4. Настроить персистентный воркер: sudo supervisorctl reread/update

### Заметки
- Различие локальных и серверных логов важно: local-лог (storage/logs/laravel.log) не отражает состояние сервера.
- github: Pavelask/events.landing (ветка main).
