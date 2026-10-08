# Анализ админки — рабочий промт

> Этот файл — исполняемый промт для будущих сессий по админке `/Users/pavelklimov/Herd/landing`.
> Задача: прочитать, проверить факты командами из раздела «Верификация», и чинить пункт за пунктом.

---

## 1. Контекст проекта

**Назначение:** лендинг событий (спикеры, расписание, галерея, регистрация через Яндекс Формы, билеты с QR-чекином, согласия на обработку ПД).

### Стек (проверено `php artisan about`)

| Компонент | Версия |
|---|---|
| Laravel | 13.7.0 |
| PHP | 8.4.25 |
| Filament | v5.6.1 |
| Livewire | v4.2.4 |
| Spatie Permission | 7.4.2 |
| Filament Shield | 4.2.0 |
| Tailwind | 4.0 — **только фронтенд**, в админке не используется |
| БД / Cache / Queue | MySQL / file / database |
| Панель | `/admin`, id `admin`, цвет Rose, шрифт Satoshi |

### Структура `app/Filament`

```
app/Filament/
├── Resources/            17 зарегистрированных ресурсов (включая 3 дубля RoleResource)
│   ├── Events/           Schemas/ + Tables/ + Pages/ + Widgets/  ← EventForm.php = 747 строк
│   ├── Participants/ AnonParticipants/     ← AnonParticipantsTable.php = 620 строк
│   ├── EventDays/ Speakers/ Guests/ Testimonials/ Faqs/
│   ├── EmailTemplates/ DocumentTemplates/ FormTemplates/   ← Настройки
│   ├── Newsletters/ Exports/ Users/
│   ├── Roles/                        ← МЁРТВЫЙ дубль Shield
│   ├── Guests/Resources/Roles/       ← МЁРТВЫЙ дубль Shield
│   └── Shield/Roles/                 ← рабочий, наследник BaseRoleResource
├── Widgets/ScheduleCalendarWidget.php
└── Pages/                             ← пусто
```

Навигация: **Мероприятия** (1-10, дыра на sort=6) · **Рассылки** (1) · **Настройки** (10-13, у `FormTemplateResource` sort отсутствует).

Сопутствующие слои: 22 модели · 8 политик · 11 сервисов · 4 Job · 7 Observer · 11 контроллеров · 2 Filament-экспортера · 1 кастомное поле `TiptapEditor` · 72 миграции.

---

## 2. Критические проблемы (проверено runtime)

### 🔴 C-1. Три копии `RoleResource`, все с slug `shield/roles`

`App\Filament\Resources\Roles\RoleResource` и `App\Filament\Resources\Guests\Resources\Roles\RoleResource` — **байт-в-байт копии** (различаются только `namespace` + 4 `use`), ворённые из вендора Shield. Рабочий только `Shield\Roles\RoleResource`.

`discoverResources` регистрирует все три, плюс `AdminPanelProvider.php:70` явно дублирует `RoleResource::class` в `->resources([...])`. Filament дедуплицирует маршруты, но в `getResources()` — все 17 записей, три с коллизией slug.

**Фикс:** удалить `app/Filament/Resources/Roles/` и `app/Filament/Resources/Guests/Resources/` (~350 строк), убрать `RoleResource::class` из `->resources()`.

### 🔴 C-2. Очередь `consents` не обрабатывается ни одним воркером

`GenerateConsentPdf.php:25` и `GenerateConsentsBatch.php:27` → `->onQueue('consents')`.
Воркеры: `--queue=default,sendnewsletter` (`deploy/supervisor/queue-worker.conf:10`, `queue-worker-macos.sh:13`).

**Генерация PDF согласий на ПД в проде не работает.** `DocxPdfGeneratorService` зовёт LibreOffice headless → таймауты → `failed_jobs`.

`SendNewsletterBatch` не задаёт `onQueue` → уходит в `default`, а воркер слушает `sendnewsletter`. Несостыковка в обе стороны.

> ⏸ **ОТЛОЖЕНО до следующего мероприятия.** Фикс: добавить `consents` в `--queue=` в обоих конфигах.

### 🔴 C-3. Уведомления об экспорте теряются

`ExportAnonParticipantsWithPdJob.php:180,195` — `Notification::make()->send()` **без `->toDatabase()` и без получателя**, из очереди. Filament такое уведомление никуда не доставляет: админ не узнает, что экспорт готов.

Плюс `session(['export_started_at' => ...])` (`AnonParticipantsTable.php:385`) — никем не читается.

**Фикс:** `->toDatabase()` + получатель `$admin->notify()`; удалить запись в сессию.

### 🔴 C-4. `ScheduleCalendarWidget` отдаёт расписание всех событий

`ScheduleCalendarWidget.php:17-27` — нет фильтра по событию, нет `where is_active`, `limit(10)` без дедупликации по дням. Дашборд показывает чужие мероприятия.

---

## 3. Архитектурные проблемы

### A-1. Бизнес-логика внутри Table-классов

`AnonParticipantsTable.php` — 620 строк, ~250 из них bulk/record-экшены с логикой в замыканиях.

`sendTickets` (`:393-440`) — синхронный `foreach` с HTTP к Yandex API **на запись** + `Mail::send()` синхронно. На 100 участниках = 100 HTTP + 100 SMTP внутри браузерного запроса → таймаут.

Дублирование: `sendTickets`/`sendTicket` (Δ8 строк), `markArrived`/`resetCheckin`.

**Фикс:** `SendTicketsJob` + сервис `Participants\Actions\SendTicket`, вызываемый из обоих мест.

### A-2. N+1 и полные `pluck` в селектах

`EventForm.php:53-79` — статические кэши `self::$speakerNames/$guestNames/$testimonialNames/$faqQuestions` с `pluck(...)->all()`: **вся таблица в память** (деградация на 10k+). Статики живут в рамках PHP-процесса, а не запроса (риск под Octane/в очереди). 4 метода `*Label()` дублируют одну логику.

**Фикс:** `->options()` + `getSearchResultsUsing()` с поиском на стороне БД.

### A-3. Tailwind 4 в админке не используется

Админка — скомпилированный CSS Filament + `public/css/filament-admin.css` (46 строк `!important`-переопределений `.fi-*`). Файл **вне `resources/`**, мимо Vite, подключается вручную в `AdminPanelProvider.php:46-52` через `filemtime()`.

Хрупко: при апгрейде Filament v5→v6 классы (`.fi-sidebar-group-dropdown-trigger-btn`) переименуются, тема молча отвалится.

**Фикс:** `resources/css/filament/admin/theme.css` + `->viteTheme()`.

### A-4. Нет Relation Managers — 747-строчная форма

`EventResource` без `getRelations()`. Все 6 pivot-связей редактируются `Repeater` внутри одной формы на 4 таба. Ошибка валидации на 4-й вкладке теряет ввод остальных 3.

### A-5. Три разных стиля организации ресурсов

| Стиль | Ресурсы |
|---|---|
| `Schemas/` + `Tables/` + `Pages/` | Events, Participants, EmailTemplates, DocumentTemplates, EventDays, Newsletters, Speakers, Testimonials, AnonParticipants, FormTemplates |
| инлайн `form()`/`table()` | Faqs, Guests, Users |
| только `table()` | Exports |
| пустой каталог `Tables/` | `Resources/Exports/Tables/` — мёртвый |

---

## 4. Качество и консистентность

### Q-1. Покрытие политиками неполное

8 политик по 74 строки (чистые permission-checks). **Нет** для: `AnonParticipant` (PII!), `EmailTemplate`, `FormTemplate`, `DocumentTemplate`, `Newsletter`, `Export`, `HeroSlide`, `ScheduleEvent`, `EventDocument`, `ConsentGenerationLog`.

`User::canAccessPanel()` возвращает `true` безусловно (`User.php:22-25`) — доступ получают все аутентифицированные; ограничение только через `canViewAny()`.

### Q-2. Pint: 40 из 91 файлов не проходят

```bash
./vendor/bin/pint --test app/Filament   # result: fail, 40 файлов
```
Затронуты почти все `Pages/*` (`fully_qualified_strict_types`, `ordered_imports`), `AnonParticipantsTable` (12 фиксеров), `EventForm` (13). Плюс `!` без пробела по всему коду.

### Q-3. Тесты: админка покрыта одним файлом

`tests/Feature/IndividualEmailSendingTest.php` — единственный тест, трогающий Filament. Нет тестов на: политики (8 файлов), 4 Job, 11 сервисов (вкл. `YandexFormsApi` на 392 строки с HTTP), 7 Observer, `YandexWebhookController`. Ни одного CRUD-теста ресурса.

### Q-4. Дыры в навигации

`sort=6` в «Мероприятия» не занят; у `FormTemplateResource` (`Настройки`) sort отсутствует → уходит в конец группы после Email-шаблонов.

### Q-5. Мёртвый код

- `resources/views/components/⚡export-status.blade.php` — пустой Livewire-компонент с цитатой о Марии Склодовской-Кюри
- `Resources/Exports/Tables/` — пустой каталог
- `Roles/` + `Guests/Resources/Roles/` — 350+ строк дублей
- `session(['export_started_at'])` — не читается

---

## 5. Что сделано хорошо — НЕ ТРОГАТЬ

- **Filament 5 idiom выдержан:** `Schemas/`, `recordActions`, `Tabs`, `Section`, `#[Override]` — не legacy `Filament\Forms\Form` из v3.
- **Кэширование грамотное:** `resolveActiveEvent()`, `resolveEventContent()`, `resolveTestimonials()` в `helpers.php` с TTL 600 + Observer-инвалидация на CUD. Правильный паттерн, не переписывать.
- **`clean_html()`** (`helpers.php:78-146`) — санитайзер с whitelist тегов/атрибутов и блокировкой `javascript:`/`data:` в href/src. Использовать для любого нового RichEditor-контента на фронте.
- **Cleanup файлов на delete:** 7 моделей (`Event`, `Guest`, `Speaker`, `Testimonial`, `HeroSlide`, `ScheduleEvent`, `EventDocument`) чистят диск в `deleted`/`forceDeleted`.
- **`EventResource::getEloquentQuery()`** eagerly грузит все 6 связей — правильно для списка/редактирования.
- **`persistTabInQueryString()`** на табах `EventForm` — грамотный UX.
- **PII-разделение:** `AnonParticipant` (Яндекс API, ПД не хранятся локально) vs `Participant` — осознанная архитектура.

---

## 6. Отложено

| # | Задача | Триггер |
|---|---|---|
| C-2 | Добавить `consents` в `--queue=` (оба конфига) | **до следующего мероприятия** |
| C-3 | `->toDatabase()` + `$admin->notify()` в экспорте | вместе с C-2 |
| C-1 | Удалить 2 дубля `RoleResource` | любая следующая сессия |
| C-4 | Фильтр по событию в `ScheduleCalendarWidget` | любая следующая сессия |

---

## 7. Верификация

Факты выше проверены runtime, не догадками. Перед правками перепроверить:

```bash
cd /Users/pavelklimov/Herd/landing

# Аудит зарегистрированных ресурсов (ловит дубли slug)
php artisan tinker --execute='
$p = Filament\Facades\Filament::getPanel("admin");
foreach ($p->getResources() as $r) {
  echo str_pad($r, 72), " slug=", $r::getSlug(), "\n";
}
echo "TOTAL: ", count($p->getResources()), "\n";'

php artisan route:list --path=admin
php artisan about

# Линтер
./vendor/bin/pint --test app/
```

**Инварианты после фиксов:**
- `getResources()` = 14 записей, один `shield/roles`, ни одного дубля slug
- `--queue=default,sendnewsletter,consents` в обоих конфигах воркеров
- `./vendor/bin/pint --test app/` → `"result":"pass"`
- `php artisan migrate` на пустой БД проходит без ошибок
