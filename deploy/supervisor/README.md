# Постоянная обработка очереди писем

Проект отправляет письма через очередь: `QUEUE_CONNECTION=database`, а `TemplateMail`
и `TicketMail` реализуют `ShouldQueue`. Без активного воркера `php artisan queue:work`
письма **никогда не уходят** — они падают в таблицу `jobs` и лежат там.

## Вариант А. Supervisor (рекомендуется, если supervisor установлен)

Бинарники: `sudo apt install supervisor` (Debian/Ubuntu)

```bash
sudo cp deploy/supervisor/queue-worker.conf /etc/supervisor/conf.d/email-queue.conf
# поправить paths если проект не в /Users/pavelklimov/Herd/landing
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status        # email_queue:email_queue_00 ... RUNNING
```

Проверка:
```bash
sudo supervisorctl restart email_queue
php artisan queue:monitor        # должно быть 0 pending / 0 failed
```

## Вариант Б. macOS (Herd) без supervisor — launchd

Воркер с автозапуском + перезапуском при падении (автономный скрипт):

```bash
chmod +x deploy/supervisor/queue-worker-macos.sh
./deploy/supervisor/queue-worker-macos.sh start    # запустить
./deploy/supervisor/queue-worker-macos.sh status
./deploy/supervisor/queue-worker-macos.sh stop
```

Внутри использовать `nohup ... &` + `pgrep` — на Herd предпочтительный простой вариант,
не требующий root.

## Проверить, что всё работает

```bash
php artisan queue:monitor
# Ожидание: 0 pending, 0 failed (не считать старые записи из failed_jobs)

# Прямой тест (в обход очереди, синхронно):
php artisan tinker
> Mail::to("you@example.com")->send(new App\Mail\TemplateMail(
>     App\Models\EmailTemplate::where("is_active", true)->first(),
>     App\Models\Event::first(),
>     App\Models\Participant::whereNotNull("email")->first(),
> ));
```

## Почему письма «не отправлялись»

1. `template_mail`/`TemplateMail` кладётся в `jobs` (database).
2. Нет `queue:work` → письма копятся в `jobs`, доставки нет.
3. В `failed_jobs` раньше были только ошибки окружения:
   - `smtp.yandex.ru:587 Connection refused` (нет доступа к SMTP);
   - отсутствовавшая таблица `notifications` (не создана миграция Filament).
