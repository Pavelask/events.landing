<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Нет подключения к интернету — {{ $event?->title ?? 'Платформа мероприятий' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        :root {
            --color-primary: #ff385c;
            --color-primary-hover: #e53e5c;
            --color-text: #1a1a1a;
            --color-text-secondary: #666;
            --color-muted: #999;
            --color-surface: #fff;
            --color-background: #f7f7f7;
            --color-border: #e5e5e5;
            --radius-card: 12px;
            --radius-btn: 8px;
        }

        [data-theme="dark"] {
            --color-primary: #ff6b8a;
            --color-primary-hover: #ff385c;
            --color-text: #f0f0f0;
            --color-text-secondary: #a0a0a0;
            --color-muted: #666;
            --color-surface: #1a1a1a;
            --color-background: #111;
            --color-border: #333;
        }

        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --color-primary: #ff6b8a;
                --color-primary-hover: #ff385c;
                --color-text: #f0f0f0;
                --color-text-secondary: #a0a0a0;
                --color-muted: #666;
                --color-surface: #1a1a1a;
                --color-background: #111;
                --color-border: #333;
            }
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--color-background);
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
            position: relative;
        }

        [data-theme="dark"] .error-card {
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
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
            white-space: nowrap;
            min-width: 0;
        }

        .btn-primary:hover {
            background: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 56, 92, 0.3);
        }

        [data-theme="dark"] .btn-primary:hover {
            box-shadow: 0 4px 12px rgba(255, 107, 138, 0.4);
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
            white-space: nowrap;
            min-width: 0;
        }

        .btn-secondary:hover {
            background: var(--color-background);
            border-color: var(--color-primary);
            color: var(--color-primary);
        }

        .theme-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 2px;
            background: var(--color-background);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-btn);
            padding: 3px;
        }

        .theme-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: var(--color-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .theme-btn:hover {
            color: var(--color-text);
        }

        .theme-btn.active {
            background: var(--color-surface);
            color: var(--color-primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        [data-theme="dark"] .theme-btn.active {
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        @media (max-width: 640px) {
            .error-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="theme-toggle">
                <button class="theme-btn" data-theme="light" title="Светлая тема">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                    </svg>
                </button>
                <button class="theme-btn" data-theme="dark" title="Тёмная тема">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </button>
                <button class="theme-btn" data-theme="system" title="Системная тема">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </button>
            </div>

            <div class="error-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-20 w-20" style="color: var(--color-primary);">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/>
                        <path d="M21 3v5h-5"/>
                    </svg>
                    Обновить
                </button>
                <a href="{{ url('/') }}" class="btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    На главную
                </a>
            </div>
        </div>
    </div>

    <script>
        // Theme management
        function getSystemTheme() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function getStoredTheme() {
            return localStorage.getItem('theme') || 'system';
        }

        function applyTheme(theme) {
            const root = document.documentElement;
            if (theme === 'system') {
                root.removeAttribute('data-theme');
            } else {
                root.setAttribute('data-theme', theme);
            }

            document.querySelectorAll('.theme-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.theme === theme);
            });
        }

        // Initialize theme
        applyTheme(getStoredTheme());

        // Theme toggle handlers
        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const theme = btn.dataset.theme;
                localStorage.setItem('theme', theme);
                applyTheme(theme);
            });
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (getStoredTheme() === 'system') {
                applyTheme('system');
            }
        });

        // Online/offline handlers
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
