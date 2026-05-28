<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация — {{ $event?->title ?? 'Платформа мероприятий' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>.navbar-scrolled {
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(18px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
            color: #000 !important
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
<body class="bg-white text-black" x-data="{menuOpen: false}">
<nav id="main-navbar" class="fixed inset-x-0 top-0 z-50 transition-all duration-300 text-white navbar-scrolled">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4"><a href="{{ url('/') }}"
                                                                                  class="flex items-center gap-3 font-black uppercase tracking-widest cursor-pointer">
            @if($event?->logo)
                <img src="{{ asset('storage/'.$event->logo) }}" class="h-10 w-10 rounded-full object-cover"
                     alt="Logo">
            @endif<span>{{ $event?->title ?? 'Fifth Event' }}</span></a>
        <div class="hidden items-center gap-6 text-sm font-semibold md:flex"><a href="{{ url('/') }}#speakers">Спикеры</a><a href="{{ url('/') }}#keynote">Гости</a><a href="{{ url('/') }}#schedule">Расписание</a><a
                href="{{ url('/') }}#documents">Документы</a><a href="{{ url('/') }}#faq">FAQ</a><a href="{{ url('/') }}#venue">Адрес</a>
        </div>
        <button class="md:hidden flex items-center gap-2" @click="menuOpen=!menuOpen">
            <span x-text="menuOpen ? '✕' : '☰'"></span>
        </button>
    </div>
    <div x-show="menuOpen" x-transition class="absolute top-full left-0 right-0 bg-white/95 backdrop-blur-md border-t border-black/10 md:hidden text-black">
        <div class="flex flex-col items-center gap-4 px-6 py-6">
            <a class="block py-2 text-base" href="{{ url('/') }}#speakers" @click="menuOpen=false">Спикеры</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#keynote" @click="menuOpen=false">Гости</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#schedule" @click="menuOpen=false">Расписание</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#documents" @click="menuOpen=false">Документы</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#faq" @click="menuOpen=false">FAQ</a>
            <a class="block py-2 text-base" href="{{ url('/') }}#venue" @click="menuOpen=false">Адрес</a>
        </div>
    </div>
</nav>

<div class="min-h-screen pt-24 pb-12">
    <div class="mx-auto max-w-4xl px-6">
        <div class="mb-8 text-center">
            <h1 class="mt-2 text-4xl font-black">{{ $event?->title ?? 'Регистрация на мероприятие' }}</h1>
        </div>

        @if(!$event)
            <div class="rounded-3xl bg-gray-100 p-8 text-center text-gray-500">
                Активное мероприятие не найдено.
            </div>
        @elseif(!$event->is_registration_open)
            <div class="rounded-3xl bg-gray-100 p-8 text-center text-gray-500">
                Регистрация для этого мероприятия пока не открыта.
            @else
                <div class="rounded-3xl bg-gray-100 p-8 text-black">
                    @if($event->registration_url)
                        <iframe src="{{ $event->registration_url }}" class="h-[720px] w-full rounded-xl bg-white" frameborder="0"></iframe>
                    @elseif($event->yandex_form_url)
                        <div class="yandex-form-container">
                            {!! $event->yandex_form_url !!}
                        </div>
                    @else
                        <p class="text-center text-gray-500">Ссылка на регистрацию не указана.</p>
                    @endif
                </div>
            @endif

        <div class="mt-8 text-center text-sm text-gray-500">
            <a href="{{ url('/') }}" class="hover:text-black transition-colors">← Вернуться на главную</a>
        </div>
    </div>
</div>

{{-- Футтер --}}
<footer class="bg-black text-white">
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid gap-10 md:grid-cols-3">
            {{-- Бренд --}}
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-3 font-black uppercase tracking-widest">
                    @if($event?->logo)
                        <img src="{{ asset('storage/'.$event->logo) }}" class="h-10 w-10 rounded-full object-cover" alt="Logo">
                    @endif
                    <span>{{ $event?->title ?? 'Fifth Event' }}</span>
                </a>
                @if($event?->description)
                    <p class="mt-4 text-sm text-gray-400">{{ Str::limit(strip_tags($event->description), 120) }}</p>
                @endif
            </div>

            {{-- Контакты --}}
            <div>
                <p class="font-bold uppercase tracking-widest text-gray-500 text-sm mb-4">Контакты</p>
                <div class="space-y-2 text-sm text-gray-300">
                    @if($event?->contact_email)
                        <p><a href="mailto:{{ $event->contact_email }}" class="hover:text-white transition-colors">{{ $event->contact_email }}</a></p>
                    @endif
                    @if($event?->contact_phone)
                        <p><a href="tel:{{ $event->contact_phone }}" class="hover:text-white transition-colors">{{ $event->contact_phone }}</a></p>
                    @endif
                    @if($event?->venue_address)
                        <p class="text-gray-400">{{ $event->venue_address }}</p>
                    @endif
                </div>
            </div>

            {{-- Соцсети + документы --}}
            <div>
                <p class="font-bold uppercase tracking-widest text-gray-500 text-sm mb-4">Ссылки</p>
                <div class="space-y-2 text-sm">
                    @if($event && $event->show_privacy_section)
                        @if($event->privacy_policy)
                            <p class="text-gray-300">Политика конфиденциальности доступна на главной странице.</p>
                        @endif
                        @if($event->personal_data_consent)
                            <p class="text-gray-300">Согласие на обработку персональных данных доступно на главной странице.</p>
                        @endif
                    @endif
                    @if($event?->social_links && is_array($event->social_links))
                        <div class="flex flex-wrap gap-3 mt-3">
                            @foreach($event->social_links as $platform => $url)
                                @if($url)
                                    <a href="{{ $url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors" title="{{ $platform }}">{{ $platform }}</a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Копирайт --}}
        <div class="mt-12 border-t border-white/10 pt-8 text-center text-xs text-gray-500">
            © {{ now()->year }} {{ $event?->title ?? 'Платформа мероприятий' }}. Все права защищены.
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
