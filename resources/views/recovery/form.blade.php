<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление билета</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .form-container { max-width: 400px; width: 100%; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .form-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; text-align: center; }
        .form-header h1 { font-size: 18px; margin-bottom: 8px; }
        .form-header p { font-size: 14px; opacity: 0.9; }
        .form-body { padding: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 14px; font-weight: 500; color: #333; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #667eea; }
        .btn { width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; }
        .btn:hover { background: #5a6fd6; }
        .error { color: #dc3545; font-size: 13px; margin-top: 4px; }
        .message { color: #28a745; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Восстановление билета</h1>
            <p>Введите email для получения кода</p>
        </div>
        <div class="form-body">
            @if($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif
            @if(session('message'))
                <div class="message">{{ session('message') }}</div>
            @endif
            <form method="POST" action="{{ route('recovery.send') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="your@email.com" value="{{ old('email') }}">
                </div>
                <button type="submit" class="btn">Отправить код</button>
            </form>
        </div>
    </div>
</body>
</html>
