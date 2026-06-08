<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Страница не найдена — {{ $event?->title ?? 'Платформа мероприятий' }}</title>
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

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, var(--color-primary) 0%, #ff6b6b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1.5rem 0 1rem;
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

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        @media (max-width: 640px) {
            .error-code {
                font-size: 6rem;
            }

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Страница не найдена</h2>
            <p class="error-message">
                @if($exception?->getMessage())
                    {{ $exception->getMessage() }}
                @else
                    К сожалению, запрашиваемая страница не найдена. Возможно, она была удалена или перемещена.
                @endif
            </p>
            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    На главную
                </a>
                <button onclick="history.back()" class="btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Назад
                </button>
            </div>
        </div>
    </div>
</body>
</html>
