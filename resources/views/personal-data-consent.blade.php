<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Согласие на обработку персональных данных — {{ $activeEvent?->title ?? 'Платформа мероприятий' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-text">

{{-- Полоса загрузки --}}
<div id="page-progress-bar"></div>

{{-- Навигация --}}
<nav class="fixed inset-x-0 top-0 z-50 transition-all duration-300 text-black bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5">
        <a href="{{ url('/') }}" class="flex items-center gap-3 font-bold uppercase tracking-wide">
            @if($activeEvent?->logo)
                <img src="{{ asset('storage/'.$activeEvent->logo) }}" class="h-10 w-10 rounded-full object-cover" alt="Logo">
            @endif
            <span class="text-sm">{{ $activeEvent?->title ?? 'Fifth Event' }}</span>
        </a>
        <a href="{{ url('/') }}" class="text-sm font-medium hover:text-[var(--color-primary)] transition-colors">← На главную</a>
    </div>
</nav>

{{-- Основной контент --}}
<main class="pt-24 pb-20">
    <div class="mx-auto max-w-4xl px-6">
        <div class="mt-8">
            <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Документы</p>
            <h1 class="text-3xl md:text-4xl font-bold text-[var(--color-text)] leading-tight">Согласие на обработку персональных данных</h1>
        </div>

        <div class="mt-12 prose prose-lg max-w-none text-[var(--color-text-secondary)]">
            @if($activeEvent && $activeEvent->personal_data_consent)
                {!! clean_html($activeEvent->personal_data_consent) !!}
            @else
                <p>Согласие на обработку персональных данных в настоящее время недоступно.</p>
            @endif
        </div>

        <div class="mt-12">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-medium hover:text-[var(--color-primary)] transition-colors">
                ← Вернуться на главную
            </a>
        </div>
    </div>
</main>

{{-- Футтер --}}
<footer class="bg-[var(--color-text)] text-white">
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid gap-10 md:grid-cols-3">
            {{-- Бренд --}}
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-3 text-xs font-semibold uppercase tracking-wide">
                    @if($activeEvent?->logo)
                        <img src="{{ asset('storage/'.$activeEvent->logo) }}" class="h-10 w-10 rounded-full object-cover" alt="Logo">
                    @endif
                    <span>{{ $activeEvent?->title ?? 'Fifth Event' }}</span>
                </a>
                @if($activeEvent?->description)
                    <p class="mt-4 text-sm text-gray-400">{{ Str::limit(strip_tags($activeEvent->description), 120) }}</p>
                @endif
            </div>

            {{-- Контакты --}}
            <div>
                <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-4">Контакты</p>
                <div class="space-y-2 text-sm text-gray-300">
                    @if($activeEvent?->contact_email)
                        <p><a href="mailto:{{ $activeEvent->contact_email }}" class="hover:text-white transition-colors">{{ $activeEvent->contact_email }}</a></p>
                    @endif
                    @if($activeEvent?->contact_phone)
                        <p><a href="tel:{{ $activeEvent->contact_phone }}" class="hover:text-white transition-colors">{{ $activeEvent->contact_phone }}</a></p>
                    @endif
                    @if($activeEvent?->venue_address)
                        <p class="text-gray-400">{{ $activeEvent->venue_address }}</p>
                    @endif
                </div>
            </div>

            {{-- Навигация --}}
            <div>
                <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-4">Навигация</p>
                <div class="space-y-2 text-sm">
                    <a href="{{ route('archive') }}" class="block text-gray-300 hover:text-white transition-colors">Архив мероприятий</a>
                    @if($activeEvent && $activeEvent->show_privacy_section && $activeEvent->privacy_policy)
                        <a href="{{ route('privacy.policy') }}" class="block text-gray-300 hover:text-white transition-colors">Политика конфиденциальности</a>
                    @endif
                    @if($activeEvent && $activeEvent->show_personal_data_consent && $activeEvent->personal_data_consent)
                        <a href="{{ route('personal.data.consent') }}" class="block text-gray-300 hover:text-white transition-colors">Обработка персональных данных</a>
                    @endif
                    <a href="{{ route('cookie.policy') }}" class="block text-gray-300 hover:text-white transition-colors">Политика использования файлов cookie</a>
                </div>
            </div>
        </div>

        {{-- Копирайт --}}
        <div class="mt-12 border-t border-white/10 pt-8 text-center text-xs text-gray-500">
            © {{ now()->year }} {{ $activeEvent?->title ?? 'Платформа мероприятий' }}. Все права защищены.
        </div>
    </div>
</footer>

@vite(['resources/js/app.js'])
@livewireScripts

<script>
    // Полоса загрузки страницы
    (function() {
        const progressBar = document.getElementById('page-progress-bar');
        
        window.addEventListener('load', function() {
            progressBar.classList.add('loading');
            setTimeout(function() {
                progressBar.classList.add('complete');
            }, 500);
        });

        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && !link.hasAttribute('data-no-progress')) {
                progressBar.classList.remove('complete');
                progressBar.classList.add('loading');
            }
        });

        window.addEventListener('beforeunload', function() {
            progressBar.classList.remove('complete');
        });
    })();
</script>
</body>
</html>
