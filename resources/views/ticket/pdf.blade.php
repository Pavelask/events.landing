<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; margin: 0; padding: 40px; background: white; }
        .ticket { max-width: 600px; margin: 0 auto; border: 2px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .header { background-color: #667eea; color: white; padding: 32px; text-align: center; }
        .header h1 { font-size: 20px; margin: 0 0 10px 0; font-weight: 700; }
        .header p { font-size: 14px; margin: 0; }
        .body { padding: 32px; }
        .info { margin-bottom: 16px; text-align: center; }
        .info label { display: block; font-size: 24px; color: #666; margin-bottom: 4px; text-transform: uppercase; font-weight: 700; }
        .info span { font-size: 30px; color: #333; font-weight: 600; }
        .qr { text-align: center; padding: 24px; background: #f9f9f9; border-radius: 8px; margin: 24px 0; }
        .qr img { width: 200px; height: 200px; }
        .ticket-number { text-align: center; margin-top: 16px; font-size: 18px; color: #666; }
        .ticket-number span { font-weight: 700; color: #333; font-size: 24px; }
        .footer { padding: 16px 32px; background: #f9f9f9; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>{{ $participant->event->title }}</h1>
            <p>{{ $participant->event->start_date->format('d.m') }} — {{ $participant->event->end_date->format('d.m.Y') }}</p>
            @if($participant->event->venue_name)
            <p>{{ $participant->event->venue_name }}</p>
            @endif
        </div>
        <div class="body">
            <div class="info">
                <label>Участник мероприятия</label>
                <span>{{ $participant->name }}</span>
            </div>
            @if($participant->email)
            <div class="info">
                <label>Email</label>
                <span>{{ $participant->email }}</span>
            </div>
            @endif
            @if($participant->phone)
            <div class="info">
                <label>Телефон</label>
                <span>{{ $participant->phone }}</span>
            </div>
            @endif
            <div class="qr">
                <img src="{{ $qrDataUrl }}" alt="QR Code">
            </div>
            <div class="ticket-number">
                <span>{{ $participant->answer_id }}</span>
            </div>
        </div>
        <div class="footer">
            Предъявите QR-код на входе в мероприятие
        </div>
    </div>
</body>
</html>
