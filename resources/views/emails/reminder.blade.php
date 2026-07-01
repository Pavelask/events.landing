<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 32px; text-align: center;">
            <h1 style="margin: 0 0 8px 0; font-size: 24px;">{{ $participant->event->title }}</h1>
            <p style="margin: 0; opacity: 0.9; font-size: 16px;">Напоминание: мероприятие завтра!</p>
        </div>
        <div style="padding: 32px;">
            <p style="font-size: 16px; color: #333; margin-bottom: 16px;">Здравствуйте, {{ $participant->name }}!</p>
            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                Напоминаем, что завтра начинается мероприятие <strong>{{ $participant->event->title }}</strong>.
            </p>
            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                <strong>Дата:</strong> {{ $participant->event->start_date->format('d.m.Y H:i') }}<br>
                @if($participant->event->venue_name)
                <strong>Место:</strong> {{ $participant->event->venue_name }}<br>
                @endif
                @if($participant->event->venue_address)
                <strong>Адрес:</strong> {{ $participant->event->venue_address }}
                @endif
            </p>
            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                Не забудьте взять с собой билет с QR-кодом. Он понадобится на входе.
            </p>
            <div style="text-align: center; margin-bottom: 24px;">
                <a href="{{ $ticketUrl }}" style="display: inline-block; padding: 14px 32px; background: #f5576c; color: white; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 500;">Открыть билет</a>
            </div>
            <p style="font-size: 12px; color: #999; text-align: center;">Если кнопка не работает, скопируйте ссылку: {{ $ticketUrl }}</p>
        </div>
    </div>
</body>
</html>
