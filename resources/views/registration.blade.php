<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация — {{ $event?->title ?? 'Платформа мероприятий' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>.navbar-scrolled {
            background: rgba(9, 9, 11, .92);
            backdrop-filter: blur(18px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, .25)
        }
        .yandex-form-container {
            width: 100%;
        }
        .yandex-form-container iframe,
        .yandex-form-container form {
            width: 100%;
        }
    </style>
</head>
<body class="bg-zinc-950 text-white" x-data="{menuOpen: false}">
<nav id="main-navbar" class="fixed inset-x-0 top-0 z-50 transition-all duration-300 navbar-scrolled">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4"><a href="{{ url('/') }}"
                                                                                  class="flex items-center gap-3 font-black uppercase tracking-widest cursor-pointer">
            @if($event?->logo)
                <img src="{{ asset('storage/'.$event->logo) }}" class="h-10 w-10 rounded-full object-cover"
                     alt="Logo">
            @endif<span>{{ $event?->title ?? 'Fifth Event' }}</span></a>
        <div class="hidden items-center gap-6 text-sm font-semibold md:flex"><a href="{{ url('/') }}">Главная</a><a
                href="{{ url('/') }}#about">О событии</a><a href="{{ url('/') }}#speakers">Спикеры</a><a href="{{ url('/') }}#keynote">Keynote</a><a href="{{ url('/') }}#schedule">Расписание</a><a
                href="{{ url('/') }}#documents">Документы</a><a href="{{ url('/') }}#faq">FAQ</a><a href="{{ url('/') }}#venue">Площадка</a><a href="{{ url('/') }}#archive">Архив</a>
        </div>
        <button class="md:hidden flex items-center gap-2" @click="menuOpen=!menuOpen">
            <span x-text="menuOpen ? '✕' : '☰'"></span>
        </button>
    </div>
    <div x-show="menuOpen" x-transition class="absolute top-full left-0 right-0 bg-black/95 backdrop-blur-md border-t border-white/10 md:hidden">
        <div class="flex flex-col items-center gap-4 px-6 py-6">
            <a class="block py-2 text-base" href="{{ url('/') }}" @click="menuOpen=false">Главная</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#about" @click="menuOpen=false">О событии</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#speakers" @click="menuOpen=false">Спикеры</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#keynote" @click="menuOpen=false">Keynote</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#schedule" @click="menuOpen=false">Расписание</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#documents" @click="menuOpen=false">Документы</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#faq" @click="menuOpen=false">FAQ</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#venue" @click="menuOpen=false">Площадка</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#archive" @click="menuOpen=false">Архив</a>
        </div>
    </div>
</nav>

<div class="min-h-screen pt-24 pb-12">
    <div class="mx-auto max-w-4xl px-6">
        <div class="mb-8 text-center">
            <h1 class="mt-2 text-4xl font-black">{{ $event?->title ?? 'Регистрация на мероприятие' }}</h1>
        </div>

        @if(!$event)
            <div class="rounded-3xl bg-white p-8 text-center text-zinc-500">
                Активное мероприятие не найдено.
            </div>
        @elseif(!$event->is_registration_open)
            <div class="rounded-3xl bg-white p-8 text-center text-zinc-500">
                Регистрация для этого мероприятия пока не открыта.
            @else
                <div class="rounded-3xl bg-white p-8 text-black">
                    @if($event->registration_url)
                        <iframe src="{{ $event->registration_url }}" class="h-[720px] w-full rounded-xl" frameborder="0"></iframe>
                    @elseif($event->yandex_form_url)
                        <div class="yandex-form-container">
                            {!! $event->yandex_form_url !!}
                        </div>
                    @else
                        <p class="text-center text-zinc-500">Ссылка на регистрацию не указана.</p>
                    @endif
                </div>
            @endif

        <div class="mt-8 text-center text-sm text-zinc-400">
            <a href="{{ url('/') }}" class="hover:text-amber-400 transition-colors">← Вернуться на главную</a>
        </div>
    </div>
</div>

@livewireScripts
</body>
</html>
