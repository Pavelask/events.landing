<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Регистрация' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://js.hcaptcha.com/1/api.js?render=explicit" async defer></script>
</head>
<body style="background-color: var(--color-surface); color: var(--color-text);">
    {{ $slot }}
    @livewireScripts
</body>
</html>
