<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест регистрации — Яндекс.Форма</title>
    <script src="https://forms.yandex.ru/_static/embed.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px; display: flex; align-items: flex-start; justify-content: center; min-height: 100vh; }
        .container { max-width: 700px; width: 100%; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; text-align: center; }
        .header h1 { font-size: 18px; margin-bottom: 8px; }
        .header p { font-size: 14px; opacity: 0.9; }
        .body { padding: 24px; }
        .info { background: #f0f4ff; border: 1px solid #c3d4ff; border-radius: 8px; padding: 16px; margin-bottom: 20px; font-size: 13px; color: #333; }
        .info strong { color: #1a56db; }
        .info code { background: #e0e7ff; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
        .form-frame { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Тест регистрации</h1>
            <p>Проверка работы Яндекс.Формы с webhook</p>
        </div>
        <div class="body">
            <div class="info">
                <strong>Для тестирования:</strong><br>
                1. Заполните форму и отправьте<br>
                2. Проверьте создание участника в <code>/admin/participants</code><br>
                3. Проверьте email с билетом<br><br>
                <strong>Webhook URL:</strong> <code>{{ url('/api/yandex/register') }}</code><br>
                <strong>Секрет:</strong> <code>{{ config('services.webhook.yandex_secret') }}</code>
            </div>
            <div class="form-frame">
                <iframe src="https://forms.yandex.ru/cloud/6a43930cd04688fb19b81772?iframe=1" frameborder="0" name="ya-form-6a43930cd04688fb19b81772" width="650"></iframe>
            </div>
        </div>
    </div>
</body>
</html>
