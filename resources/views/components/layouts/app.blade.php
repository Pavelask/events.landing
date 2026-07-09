<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Регистрация' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body style="background-color: var(--color-surface); color: var(--color-text);">

<nav class="fixed inset-x-0 top-0 z-50 bg-white border-b border-gray-200">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3">
        <a href="{{ route('home') }}" class="flex items-center gap-3 font-bold uppercase tracking-wide text-black">
            @if(!empty($event) && $event?->logo)
                <img src="{{ asset('storage/'.$event->logo) }}" class="h-10 w-10 rounded-full object-cover" alt="Logo">
            @endif
            <span class="text-sm">{{ $event?->title ?? 'Fifth Event' }}</span>
        </a>
        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-[var(--color-primary)] transition-colors">
            На главную
        </a>
    </div>
</nav>

<main class="pt-16">
    {{ $slot }}
</main>

<footer class="bg-[var(--color-text)] text-white mt-16">
    <div class="mx-auto max-w-7xl px-6 py-12">
        <div class="grid gap-10 md:grid-cols-3">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 text-xs font-semibold uppercase tracking-wide">
                    @if(!empty($event) && $event?->logo)
                        <img src="{{ asset('storage/'.$event->logo) }}" class="h-10 w-10 rounded-full object-cover" alt="Logo" loading="lazy">
                    @endif
                    <span>{{ $event?->title ?? 'Fifth Event' }}</span>
                </a>
            </div>
            <div>
                <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-4">Навигация</p>
                <div class="space-y-2 text-sm">
                    <a href="{{ route('home') }}" class="block text-gray-300 hover:text-white transition-colors">Главная</a>
                    <a href="{{ route('archive') }}" class="block text-gray-300 hover:text-white transition-colors">Архив мероприятий</a>
                    <a href="{{ route('recovery.form') }}" class="block text-gray-300 hover:text-white transition-colors">Восстановить билет</a>
                </div>
            </div>
            <div>
                <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-4">Контакты</p>
                <div class="space-y-2 text-sm text-gray-300">
                    @if(!empty($event) && $event?->contact_email)
                        <p><a href="mailto:{{ $event->contact_email }}" class="hover:text-white transition-colors">{{ $event->contact_email }}</a></p>
                    @endif
                    @if(!empty($event) && $event?->contact_phone)
                        <p><a href="tel:{{ $event->contact_phone }}" class="hover:text-white transition-colors">{{ $event->contact_phone }}</a></p>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-8 border-t border-white/10 pt-6 text-center text-xs text-gray-500">
            © {{ now()->year }} {{ $event?->title ?? 'Платформа мероприятий' }}. Все права защищены.
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
