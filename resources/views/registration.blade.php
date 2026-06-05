<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация — {{ $event?->title ?? 'Платформа мероприятий' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>.yandex-form-container {
            width: 100%;
        }
        .yandex-form-container iframe,
        .yandex-form-container form {
            width: 100%;
        }
        
        #main-navbar {
            background-color: rgba(255, 255, 255, 0);
            backdrop-filter: none;
            transition: background-color 0.3s ease, backdrop-filter 0.3s ease, box-shadow 0.3s ease;
        }
        
        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(16px) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
            color: var(--color-text) !important;
        }
    </style>
</head>
<body class="bg-white text-[var(--color-text)]" x-data="{menuOpen: false}">
<nav id="main-navbar" class="fixed inset-x-0 top-0 z-50 transition-all duration-300 text-black navbar-scrolled">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5"><a href="{{ url('/') }}"
                                                                                  class="flex items-center gap-3 font-bold uppercase tracking-wide cursor-pointer">
            @if($event?->logo)
                <img src="{{ asset('storage/'.$event->logo) }}" class="h-10 w-10 rounded-full object-cover"
                     alt="Logo">
            @endif<span>{{ $event?->title ?? 'Fifth Event' }}</span></a>
        <div class="hidden items-center gap-6 text-sm font-medium md:flex"><a href="{{ url('/') }}#speakers" class="hover:text-[var(--color-primary)] transition-colors">Спикеры</a><a href="{{ url('/') }}#keynote" class="hover:text-[var(--color-primary)] transition-colors">Гости</a><a href="{{ url('/') }}#schedule" class="hover:text-[var(--color-primary)] transition-colors">Расписание</a><a
                href="{{ url('/') }}#documents" class="hover:text-[var(--color-primary)] transition-colors">Документы</a><a href="{{ url('/') }}#faq" class="hover:text-[var(--color-primary)] transition-colors">FAQ</a><a href="{{ url('/') }}#venue" class="hover:text-[var(--color-primary)] transition-colors">Адрес</a>
        </div>
        <button class="md:hidden flex items-center gap-2" @click="menuOpen=!menuOpen">
            <span x-text="menuOpen ? '✕' : '☰'" class="text-xl font-bold"></span>
        </button>
    </div>
    <div x-show="menuOpen" x-transition class="absolute top-full left-0 right-0 bg-white/95 backdrop-blur-md md:hidden text-black">
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
            <h1 class="mt-2 text-4xl font-bold text-[var(--color-text)]">{{ $event?->title ?? 'Регистрация на мероприятие' }}</h1>
        </div>

        @if(!$event)
            <div class="rounded-[var(--radius-card)] bg-[var(--color-background)] p-8 text-center text-[var(--color-muted)]">
                Активное мероприятие не найдено.
            </div>
        @elseif(!$event->is_registration_open)
            <div class="rounded-[var(--radius-card)] bg-[var(--color-background)] p-8 text-center text-[var(--color-muted)]">
                Регистрация для этого мероприятия пока не открыта.
            @else
                <div class="rounded-[var(--radius-card)] bg-[var(--color-background)] p-8 text-[var(--color-text)]">
                    @if($event->registration_url)
                        <iframe src="{{ $event->registration_url }}" class="h-[720px] w-full rounded-[var(--radius-card)] bg-white" frameborder="0"></iframe>
                    @elseif($event->yandex_form_url)
                        <div class="yandex-form-container">
                            {!! $event->yandex_form_url !!}
                        </div>
                    @else
                        <p class="text-center text-[var(--color-muted)]">Ссылка на регистрацию не указана.</p>
                    @endif
                </div>
            @endif

        <div class="mt-8 text-center text-sm text-[var(--color-muted)]">
            <a href="{{ url('/') }}" class="hover:text-[var(--color-primary)] transition-colors">← Вернуться на главную</a>
        </div>
    </div>
</div>

{{-- Футтер --}}
<footer class="bg-[var(--color-text)] text-white">
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid gap-10 md:grid-cols-3">
            {{-- Бренд --}}
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-3 font-bold uppercase tracking-wide">
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
                <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-4">Контакты</p>
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

            {{-- Документы и соцсети --}}
            <div>
                <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-4">Документы</p>
                <div class="space-y-2 text-sm text-gray-300">
                    @if($event && $event->show_privacy_section)
                        @if($event->privacy_policy)
                            <p>Политика конфиденциальности доступна на главной странице.</p>
                        @endif
                        @if($event->personal_data_consent)
                            <p>Согласие на обработку персональных данных доступно на главной странице.</p>
                        @endif
                    @endif
                </div>

                @if($event?->social_links && is_array($event->social_links))
                    <div class="mt-6">
                        <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-3">Социальные сети</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($event->social_links as $social)
                                @if(is_array($social) && !empty($social['url']))
                                    @php
                                        $platform = strtolower($social['platform'] ?? '');
                                        $icon = $social['icon'] ?? null;
                                        $url = $social['url'];
                                    @endphp
                                    <a href="{{ $url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors" title="{{ ucfirst($social['platform'] ?? 'Social') }}">
                                        @if($icon && file_exists(public_path('storage/icons/' . $icon . '.png')))
                                            <img src="{{ asset('storage/icons/' . $icon . '.png') }}" alt="{{ $social['platform'] }}" class="w-6 h-6 social-icon object-contain">
                                        @elseif($icon && file_exists(public_path('storage/icons/' . $icon . '.svg')))
                                            <img src="{{ asset('storage/icons/' . $icon . '.svg') }}" alt="{{ $social['platform'] }}" class="w-6 h-6 social-icon object-contain">
                                        @elseif($platform === 'telegram' || $platform === 'tg')
                                            <x-social-icons.telegram class="w-6 h-6 social-icon" />
                                        @elseif($platform === 'vk' || $platform === 'vkontakte')
                                            <x-social-icons.vk class="w-6 h-6 social-icon" />
                                        @elseif($platform === 'youtube' || $platform === 'yt')
                                            <x-social-icons.youtube class="w-6 h-6 social-icon" />
                                        @elseif($platform === 'rutube')
                                            <x-social-icons.rutube class="w-6 h-6 social-icon" />
                                        @elseif($platform === 'ok' || $platform === 'odnoklassniki')
                                            <x-social-icons.ok class="w-6 h-6 social-icon" />
                                        @elseif($platform === 'max')
                                            <x-social-icons.max class="w-6 h-6 social-icon" />
                                        @else
                                            <x-heroicon-o-link class="w-6 h-6 social-icon social-icon-default" />
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
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
