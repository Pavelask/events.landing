<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чек-ин</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .result { max-width: 400px; width: 100%; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; text-align: center; }
        .result-header { padding: 32px 24px; }
        .badge { display: inline-block; padding: 12px 24px; border-radius: 24px; font-size: 16px; font-weight: 600; margin-bottom: 16px; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-error { background: #f8d7da; color: #721c24; }
        .icon { font-size: 48px; margin-bottom: 16px; }
        .result-header h1 { font-size: 24px; color: #333; margin-bottom: 8px; }
        .result-header p { font-size: 16px; color: #666; margin-bottom: 4px; }
        .result-header .time { font-size: 14px; color: #999; }
        .result-body { padding: 0 24px 24px; }
        .participant-info { text-align: left; background: #f9f9f9; border-radius: 8px; padding: 16px; margin-top: 16px; }
        .participant-info label { display: block; font-size: 12px; color: #666; margin-bottom: 4px; text-transform: uppercase; }
        .participant-info span { font-size: 14px; color: #333; }
    </style>
</head>
<body>
    <div class="result">
        <div class="result-header">
            @if($alreadyCheckedIn)
                <div class="icon">⚠️</div>
                <span class="badge badge-warning">Уже отмечен</span>
                <h1>{{ $participant->name }}</h1>
                <p>Участник уже прошёл чек-ин</p>
                <p class="time">Время отметки: {{ $participant->checked_in_at->format('d.m.Y H:i') }}</p>
            @else
                <div class="icon">✅</div>
                <span class="badge badge-success">Успешно отмечен</span>
                <h1>{{ $participant->name }}</h1>
                <p>Добро пожаловать!</p>
                <p class="time">{{ now()->format('d.m.Y H:i') }}</p>
            @endif
        </div>
        <div class="result-body">
            <div class="participant-info">
                <label>Мероприятие</label>
                <span>{{ $participant->event->title }}</span>
            </div>
            <div class="participant-info">
                <label>Дата мероприятия</label>
                <span>{{ $participant->event->start_date->format('d.m.Y H:i') }}</span>
            </div>
        </div>
    </div>
</body>
</html>
