<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key' => 'registration-confirmation',
                'name' => 'Подтверждение регистрации',
                'subject' => 'Регистрация подтверждена: {{ event_title }}',
                'content' => <<<'HTML'
<h2 style="margin:0 0 16px;color:#333;font-size:20px;">Регистрация подтверждена</h2>
<p style="margin:0 0 16px;color:#333;font-size:16px;">Вы успешно зарегистрировались!</p>
<p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">Ваша регистрация на мероприятие <strong>{{ event_title }}</strong> подтверждена.</p>
<div style="background:#f9f9f9;border-radius:8px;padding:20px 16px;margin:0 0 24px;">
  <p style="margin:0 0 8px;font-size:14px;color:#666;">Номер регистрации:</p>
  <p style="margin:0;font-size:18px;font-weight:bold;color:#333;">#{{ participant_id }}</p>
</div>
<p style="margin:0;font-size:13px;color:#999;text-align:center;">Билет будет отправлен отдельно.</p>
HTML,
            ],
            [
                'key' => 'ticket',
                'name' => 'Билет участника',
                'subject' => 'Ваш билет: {{ event_title }}',
                'content' => <<<'HTML'
<h2 style="margin:0 0 16px;color:#333;font-size:20px;">{{ event_title }}</h2>
<p style="margin:0 0 16px;color:#666;font-size:14px;">{{ event_date }}</p>
<p style="margin:0 0 16px;color:#333;font-size:16px;">Уважаемый участник мероприятия!</p>
<p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">Ваш билет на мероприятие <strong>{{ event_title }}</strong> готов. Предъявите QR-код на входе.</p>
<div style="text-align:center;margin:0 0 16px;">
  <a href="{{ ticket_url }}" style="display:inline-block;padding:14px 32px;background:#667eea;color:#fff;text-decoration:none;border-radius:6px;font-size:16px;font-weight:500;">Открыть билет</a>
</div>
<div style="text-align:center;margin:0 0 24px;">
  <a href="{{ ticket_url }}/pdf" style="display:inline-block;padding:10px 24px;background:transparent;color:#667eea;text-decoration:none;border-radius:6px;font-size:14px;border:1px solid #667eea;">Скачать PDF</a>
</div>
<p style="margin:0;font-size:12px;color:#999;text-align:center;">Если кнопки не работают, скопируйте ссылку: {{ ticket_url }}</p>
HTML,
            ],
            [
                'key' => 'ticket-reminder',
                'name' => 'Напоминание (завтра)',
                'subject' => 'Напоминание: {{ event_title }} — завтра!',
                'content' => <<<'HTML'
<h2 style="margin:0 0 8px;color:#333;font-size:24px;">{{ event_title }}</h2>
<p style="margin:0 0 16px;color:#666;font-size:16px;">Напоминание: мероприятие завтра!</p>
<p style="margin:0 0 16px;color:#333;font-size:16px;">Здравствуйте, {{ full_name }}!</p>
<p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">Напоминаем, что завтра начинается мероприятие <strong>{{ event_title }}</strong>.</p>
<div style="background:#f9f9f9;border-radius:8px;padding:20px 16px;margin:0 0 24px;">
  <p style="margin:0 0 6px;font-size:14px;color:#666;"><strong>Дата:</strong> {{ event_start_datetime }}</p>
  <p style="margin:0 0 6px;font-size:14px;color:#666;"><strong>Место:</strong> {{ venue_name }}</p>
  <p style="margin:0;font-size:14px;color:#666;"><strong>Адрес:</strong> {{ venue_address }}</p>
</div>
<p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">Не забудьте взять с собой билет с QR-кодом. Он понадобится на входе.</p>
<div style="text-align:center;">
  <a href="{{ ticket_url }}" style="display:inline-block;padding:14px 32px;background:#f5576c;color:#fff;text-decoration:none;border-radius:6px;font-size:16px;font-weight:500;">Открыть билет</a>
</div>
HTML,
            ],
            [
                'key' => 'verification-code',
                'name' => 'Код подтверждения (восстановление билета)',
                'subject' => 'Код подтверждения для восстановления билета',
                'content' => <<<'HTML'
<p style="margin:0 0 16px;color:#333;font-size:16px;">Здравствуйте, {{ full_name }}!</p>
<p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">Вы запросили восстановление билета на мероприятие <strong>{{ event_title }}</strong>. Используйте следующий код для подтверждения:</p>
<div style="text-align:center;margin:0 0 24px;padding:24px;background:#f9f9f9;border-radius:8px;">
  <span style="font-size:36px;font-weight:bold;letter-spacing:6px;color:#333;">{{ verification_code }}</span>
</div>
<p style="margin:0 0 8px;font-size:13px;color:#666;text-align:center;">Введите этот код на странице восстановления</p>
<p style="margin:0;font-size:12px;color:#999;text-align:center;">Код действителен в течение 15 минут</p>
HTML,
            ],

            // Примеры рассылочных шаблонов
            [
                'key' => 'newsletter-welcome',
                'name' => 'Добро пожаловать (пример)',
                'subject' => 'Спасибо за регистрацию на {{ event_title }}',
                'content' => <<<'HTML'
<p style="margin:0 0 16px;color:#333;font-size:16px;">Здравствуйте, {{ full_name }}!</p>
<p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">Спасибо, что зарегистрировались на <strong>{{ event_title }}</strong>. Следите за обновлениями — скоро пришлём программу мероприятия.</p>
<div style="text-align:center;">
  <a href="{{ event_url }}" style="display:inline-block;padding:14px 32px;background:#667eea;color:#fff;text-decoration:none;border-radius:6px;font-size:16px;font-weight:500;">Смотреть сайт мероприятия</a>
</div>
HTML,
                'variables' => ['full_name', 'event_title'],
            ],
            [
                'key' => 'newsletter-after-event',
                'name' => 'Благодарность после мероприятия (пример)',
                'subject' => 'Спасибо, что были с нами: {{ event_title }}',
                'content' => <<<'HTML'
<p style="margin:0 0 16px;color:#333;font-size:16px;">Здравствуйте, {{ full_name }}!</p>
<p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">Мероприятие <strong>{{ event_title }}</strong> завершилось. Спасибо, что были с нами! Ждём вас на новых событиях.</p>
<p style="margin:0;font-size:12px;color:#999;">С уважением, команда организаторов</p>
HTML,
                'variables' => ['full_name', 'event_title'],
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::query()->updateOrCreate(
                ['key' => $template['key']],
                [
                    'name' => $template['name'],
                    'subject' => $template['subject'],
                    'content' => $template['content'],
                    'variables' => $template['variables'] ?? null,
                    'is_active' => true,
                ],
            );
        }
    }
}
