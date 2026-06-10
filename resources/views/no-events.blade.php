<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Мероприятия временно недоступны</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-text">

{{-- Полоса загрузки --}}
<div id="page-progress-bar"></div>

{{-- Навигация --}}
<nav class="fixed inset-x-0 top-0 z-50 transition-all duration-300 text-black bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5">
        <a href="{{ url('/') }}" class="flex items-center gap-3 font-bold uppercase tracking-wide">
            <span class="text-sm">Fifth Event</span>
        </a>
    </div>
</nav>

{{-- Основной контент --}}
<main class="pt-32 pb-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mt-16 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-[var(--color-background)] mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-12 h-12 text-[var(--color-primary)]">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                </svg>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-bold text-[var(--color-text)] mb-4">
                Мероприятия временно недоступны
            </h1>
            
            <p class="text-lg text-[var(--color-text-secondary)] max-w-2xl mx-auto">
                В настоящее время нет доступных мероприятий. Проверьте позже или свяжитесь с организаторами для получения информации о предстоящих событиях.
            </p>
        </div>
    </div>
</main>

{{-- Футтер --}}
<footer class="bg-[var(--color-text)] text-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        <div class="mt-12 border-t border-white/10 pt-8 text-center text-xs text-gray-500">
            © {{ now()->year }} Fifth Event. Все права защищены.
        </div>
    </div>
</footer>

@vite(['resources/js/app.js'])

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
