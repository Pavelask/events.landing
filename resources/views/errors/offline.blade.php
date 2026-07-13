<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Нет подключения к интернету — {{ $event?->title ?? 'Платформа мероприятий' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .error-card {
            border: 1px solid var(--color-border);
            border-radius: var(--radius-card);
            background: var(--color-surface);
            padding: 3rem 2rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            box-sizing: border-box;
            min-width: 0;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--color-text);
        }

        .error-message {
            font-size: 1rem;
            color: var(--color-text-secondary);
            line-height: 1.6;
            margin-bottom: 2rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            max-width: 100%;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .connection-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-btn);
            background: var(--color-background);
            color: var(--color-muted);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--color-primary);
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .btn-primary {
            background: var(--color-primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-btn);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            overflow: hidden;
            min-width: 0;
        }

        .btn-primary:hover {
            background: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 56, 92, 0.3);
        }

        .btn-secondary {
            background: var(--color-surface);
            color: var(--color-text);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-btn);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            overflow: hidden;
            min-width: 0;
        }

        .btn-secondary:hover {
            background: var(--color-background);
            border-color: var(--color-primary);
            color: var(--color-primary);
        }

        @media (max-width: 640px) {
            .error-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-surface text-text">
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-20 w-20 text-[var(--color-primary)]">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                </svg>
            </div>

            <div class="connection-status">
                <span class="status-dot"></span>
                Нет подключения к интернету
            </div>

            <h1 class="error-title">Похоже, вы офлайн</h1>
            <p class="error-message">
                Проверьте подключение к сети и попробуйте снова. Мы обновим страницу автоматически, когда соединение будет восстановлено.
            </p>

            <div class="error-actions">
                <button onclick="window.location.reload()" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Обновить страницу
                </button>
                <a href="{{ url('/') }}" class="btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    На главную
                </a>
            </div>
        </div>
    </div>

    <script>
        function updateOnlineStatus() {
            if (navigator.onLine) {
                window.location.reload();
            }
        }

        window.addEventListener('online', updateOnlineStatus);

        async function checkConnection() {
            try {
                const response = await fetch('/health', {
                    method: 'HEAD',
                    cache: 'no-cache'
                });
                if (response.ok && navigator.onLine) {
                    window.location.reload();
                }
            } catch (e) {
                // no connection
            }
        }

        setInterval(checkConnection, 30000);
    </script>
</body>
</html>
