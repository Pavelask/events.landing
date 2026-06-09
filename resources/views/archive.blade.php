<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Архив мероприятий</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-text">

<main class="mx-auto max-w-7xl px-6 py-12">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[var(--color-primary)] hover:text-[var(--color-primary-hover)] transition-colors font-medium">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        На главную
    </a>

    <h1 class="mt-8 text-4xl font-bold">Архив мероприятий</h1>

    <script>
        // Офлайн-уведомление
        (function() {
            const notification = document.createElement('div');
            notification.id = 'offline-notification';
            notification.className = 'fixed inset-x-0 bottom-0 z-50 hidden bg-[var(--color-primary)] text-white p-4 text-center font-medium';
            notification.innerHTML = `
                <div class="mx-auto max-w-7xl flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                    </svg>
                    <span>Нет подключения к интернету. Некоторые функции могут быть недоступны.</span>
                </div>
            `;
            document.body.appendChild(notification);

            window.addEventListener('online', () => {
                notification.classList.add('hidden');
            });

            window.addEventListener('offline', () => {
                notification.classList.remove('hidden');
            });

            if (!navigator.onLine) {
                notification.classList.remove('hidden');
            }
        })();
    </script>

    @if ($lastCompletedEvent)
        <div class="mt-8 archive-banner relative flex h-[450px] items-center justify-center rounded-[var(--radius-card)] bg-cover bg-center overflow-hidden"
            style="background-image: url('{{ $lastCompletedEvent->poster_image ? Storage::url($lastCompletedEvent->poster_image) : ($lastCompletedEvent->heroSlides->first()?->image ? Storage::url($lastCompletedEvent->heroSlides->first()->image) : '') }}');">
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="relative z-10 px-4 text-center text-white">
                @if ($lastCompletedEvent->logo)
                    <img src="{{ Storage::url($lastCompletedEvent->logo) }}" alt="logo" class="mx-auto mb-4 max-h-20 rounded-[var(--radius-round)]">
                @endif
                <h2 class="mb-2 text-4xl font-bold">{{ $lastCompletedEvent->title }}</h2>
                <p class="mb-2 text-xl">{{ $lastCompletedEvent->start_date->format('d M Y') }} - {{ $lastCompletedEvent->end_date->format('d M Y') }}</p>
                <p class="mb-6 text-gray-200">{{ Str::limit($lastCompletedEvent->description, 100) }}</p>
                <a href="{{ route('event.show', $lastCompletedEvent->slug) }}" class="inline-block rounded-[var(--radius-btn)] border border-white px-6 py-2 transition hover:bg-white hover:text-black">
                    Подробнее
                </a>
            </div>
        </div>
    @else
        <p class="mt-8 text-[var(--color-text-secondary)]">В архиве пока нет завершённых мероприятий.</p>
    @endif
</main>

@vite(['resources/js/app.js'])
@livewireScripts
</body>
</html>
