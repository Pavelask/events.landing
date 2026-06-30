<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 32px; text-align: center;">
            <h1 style="margin: 0 0 8px 0; font-size: 24px;">{{ $participant->event->title }}</h1>
            <p style="margin: 0; opacity: 0.9;">{{ $participant->event->start_date->format('d.m.Y H:i') }}</p>
        </div>
        <div style="padding: 32px;">
            <p style="font-size: 16px; color: #333; margin-bottom: 16px;">Здравствуйте, {{ $participant->name }}!</p>
            <p style="font-size: 14px; color: #666; margin-bottom: 24px;">Ваш билет на мероприятие готов. Нажмите кнопку ниже, чтобы открыть его.</p>
            <div style="text-align: center; margin-bottom: 24px;">
                <a href="{{ $ticketUrl }}" style="display: inline-block; padding: 14px 32px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 500;">Открыть билет</a>
            </div>
            <p style="font-size: 12px; color: #999; text-align: center;">Если кнопка не работает, скопируйте ссылку: {{ $ticketUrl }}</p>
        </div>
    </div>
</body>
</html>
