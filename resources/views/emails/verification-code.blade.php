<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 32px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Код подтверждения</h1>
        </div>
        <div style="padding: 32px;">
            <p style="font-size: 16px; color: #333; margin-bottom: 16px;">Здравствуйте, {{ $participant->name }}!</p>
            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                Вы запросили восстановление билета на мероприятие
                <strong>{{ $participant->event->title }}</strong>.
                Используйте следующий код для подтверждения:
            </p>
            <div style="text-align: center; margin-bottom: 24px; padding: 24px; background: #f9f9f9; border-radius: 8px;">
                <span style="font-size: 36px; font-weight: bold; letter-spacing: 6px; color: #333;">{{ $participant->verification_code }}</span>
            </div>
            <p style="font-size: 13px; color: #666; text-align: center; margin-bottom: 8px;">Введите этот код на странице восстановления</p>
            <p style="font-size: 12px; color: #999; text-align: center;">Код действителен в течение 15 минут</p>
        </div>
    </div>
</body>
</html>
