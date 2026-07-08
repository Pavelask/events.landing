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
    {{ $slot }}
    @livewireScripts
</body>
</html>
