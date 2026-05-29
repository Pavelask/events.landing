<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[var(--color-text)]">
<main class="mx-auto max-w-5xl px-6 py-12">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[var(--color-primary)] hover:text-[var(--color-primary-hover)] transition-colors font-medium">
        <svg class="w-5 h-5 rounded-[var(--radius-round)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        На главную
    </a>
    <h1 class="mt-8 text-5xl font-bold text-[var(--color-text)]">{{ $event->title }}</h1>
    <p class="mt-4 text-[var(--color-text-secondary)]">{{ $event->description }}</p>
    <div class="mt-8 rounded-[var(--radius-card)] bg-[var(--color-background)] p-6">
        <div class="text-[var(--color-text)]">{{ $event->start_date->format('d.m.Y') }} - {{ $event->end_date->format('d.m.Y') }}</div>
    </div>
</main>
</body>
</html>
