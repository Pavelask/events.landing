<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('heading', 'Мероприятие')</title>
</head>
<body style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background:#f5f5f5;margin:0;padding:20px;">
<div style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;">
    <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#ffffff;padding:28px 24px;text-align:center;">
        <p style="margin:0;font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;opacity:.8;">Мероприятие</p>
        <h1 style="margin:6px 0 0;font-size:22px;font-weight:700;">@yield('heading', 'Важное сообщение')</h1>
    </div>
    <div style="padding:32px 24px;line-height:1.6;color:#333;">
        @yield('content')
    </div>
    <div style="padding:20px 24px;text-align:center;border-top:1px solid #ececec;">
        <p style="margin:0;font-size:12px;color:#999;">© {{ date('Y') }} Elprof Events. Все права защищены.</p>
    </div>
</div>
</body>
</html>