<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Билет: {{ $participant->event->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px; }
        .ticket { max-width: 400px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .ticket-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; text-align: center; }
        .ticket-header h1 { font-size: 18px; margin-bottom: 8px; }
        .ticket-header p { font-size: 14px; opacity: 0.9; }
        .ticket-body { padding: 24px; }
        .ticket-info { margin-bottom: 20px; }
        .ticket-info label { display: block; font-size: 12px; color: #666; margin-bottom: 4px; }
        .ticket-info span { font-size: 16px; font-weight: 500; color: #333; }
        .qr-code { text-align: center; padding: 20px; background: #f9f9f9; border-radius: 8px; margin: 20px 0; }
        .qr-code svg { max-width: 100%; height: auto; }
        .ticket-footer { padding: 16px 24px; background: #f9f9f9; text-align: center; }
        .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500; }
        .btn:hover { background: #5a6fd6; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>{{ $participant->event->title }}</h1>
            <p>{{ $participant->event->start_date->format('d.m.Y H:i') }}</p>
        </div>
        <div class="ticket-body">
            <div class="ticket-info">
                <label>Участник</label>
                <span>{{ $participant->name }}</span>
            </div>
            @if($participant->email)
            <div class="ticket-info">
                <label>Email</label>
                <span>{{ $participant->email }}</span>
            </div>
            @endif
            @if($participant->phone)
            <div class="ticket-info">
                <label>Телефон</label>
                <span>{{ $participant->phone }}</span>
            </div>
            @endif
            <div class="qr-code">
                {!! $qrcode !!}
            </div>
        </div>
    </div>
</body>
</html>
