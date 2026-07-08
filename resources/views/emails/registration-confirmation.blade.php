<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 32px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Регистрация подтверждена</h1>
        </div>
        <div style="padding: 32px;">
            <p style="font-size: 16px; color: #333; margin-bottom: 16px;">Вы успешно зарегистрировались!</p>
            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                Ваша регистрация на мероприятие <strong>{{ $eventTitle }}</strong> подтверждена.
            </p>
            <div style="background: #f9f9f9; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
                <p style="margin: 0 0 8px 0; font-size: 14px; color: #666;">Номер регистрации:</p>
                <p style="margin: 0; font-size: 18px; font-weight: bold; color: #333;">#{{ $participant->id }}</p>
            </div>
            <p style="font-size: 13px; color: #999; text-align: center;">Билет будет отправлен отдельно.</p>
        </div>
    </div>
</body>
</html>
