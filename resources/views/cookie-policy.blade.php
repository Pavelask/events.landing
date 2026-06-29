<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Политика использования файлов cookie — {{ $activeEvent?->title ?? 'Платформа мероприятий' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-text">

<div id="page-progress-bar"></div>

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

<main class="pt-24 pb-20">
    <div class="mx-auto max-w-4xl px-6">
        <div class="mt-8">
            <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Документы</p>
        </div>

        <div class="privacy-content mt-12 max-w-none text-[var(--color-text-secondary)]" style="line-height:1.8;">
            <style>
                .privacy-content h2 { font-size:1.5rem; font-weight:700; color:var(--color-text); margin:2rem 0 1rem; }
                .privacy-content h3 { font-size:1.25rem; font-weight:600; color:var(--color-text); margin:1.5rem 0 0.75rem; }
                .privacy-content h4 { font-size:1.1rem; font-weight:600; color:var(--color-text); margin:1.25rem 0 0.5rem; }
                .privacy-content p { margin:0.75rem 0; }
                .privacy-content ul, .privacy-content ol { margin:0.75rem 0; padding-left:1.5rem; }
                .privacy-content li { margin:0.25rem 0; }
                .privacy-content a { color:var(--color-primary); text-decoration:underline; }
                .privacy-content a:hover { opacity:0.8; }
                .privacy-content blockquote { border-left:3px solid var(--color-primary); padding-left:1rem; margin:1rem 0; color:var(--color-text-secondary); font-style:italic; }
            </style>
            @if($activeEvent && $activeEvent->privacy_cookie_policy)
                {!! clean_html($activeEvent->privacy_cookie_policy) !!}
            @else
                <p>Политика использования файлов cookie в настоящее время недоступна.</p>
            @endif
        </div>

        <div class="mt-12">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-medium hover:text-[var(--color-primary)] transition-colors">
                ← Вернуться на главную
            </a>
        </div>
    </div>
</main>

<footer class="bg-[var(--color-text)] text-white">
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid gap-10 md:grid-cols-3">
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

        <div class="mt-12 border-t border-white/10 pt-8 text-center text-xs text-gray-500">
            © {{ now()->year }} {{ $activeEvent?->title ?? 'Платформа мероприятий' }}. Все права защищены.
        </div>
    </div>
</footer>

@vite(['resources/js/app.js'])
@livewireScripts

<script>
    (function() {
        const progressBar = document.getElementById('page-progress-bar');
        window.addEventListener('load', function() {
            progressBar.classList.add('loading');
            setTimeout(function() { progressBar.classList.add('complete'); }, 500);
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
