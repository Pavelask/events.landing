<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 32px; text-align: center;">
            <h1 style="margin: 0 0 12px 0; font-size: 22px; font-weight: 700;">{{ $participant->event->title }}</h1>
            <p style="margin: 0; font-size: 18px; font-weight: 600;">
                {{ $participant->event->start_date->format('d.m.Y') }} — {{ $participant->event->end_date->format('d.m.Y') }}
            </p>
        </div>
        <div style="padding: 32px;">
            <p style="font-size: 16px; color: #333; margin-bottom: 16px;">Уважаемый участник мероприятия!</p>
            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                Ваш билет на мероприятие <strong>{{ $participant->event->title }}</strong> готов.
                Предъявите QR-код на входе.
            </p>
            @if($participant->event->venue_name || $participant->event->venue_address)
            <div style="background: #f9f9f9; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
                <p style="margin: 0 0 12px 0; font-size: 14px; color: #666; text-transform: uppercase; font-weight: 600;">Место проведения</p>
                @if($participant->event->venue_name)
                <p style="margin: 0 0 6px 0; font-size: 18px; color: #333; font-weight: 600;">{{ $participant->event->venue_name }}</p>
                @endif
                @if($participant->event->venue_address)
                <p style="margin: 0; font-size: 16px; color: #666;">{{ $participant->event->venue_address }}</p>
                @endif
            </div>
            @endif
            <div style="text-align: center; margin-bottom: 16px;">
                <a href="{{ $ticketUrl }}" style="display: inline-block; padding: 14px 32px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 500;">Открыть билет</a>
            </div>
            <div style="text-align: center; margin-bottom: 24px;">
                <a href="{{ $ticketUrl }}/pdf" style="display: inline-block; padding: 10px 24px; background: transparent; color: #667eea; text-decoration: none; border-radius: 6px; font-size: 14px; border: 1px solid #667eea;">Скачать PDF</a>
            </div>
            <p style="font-size: 12px; color: #999; text-align: center;">Если кнопки не работают, скопируйте ссылку: {{ $ticketUrl }}</p>
        </div>
    </div>
</body>
</html>
