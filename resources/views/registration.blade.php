@php
    $startDate = $event && $event->start_date->isFuture() ? $event->start_date->toIso8601String() : null;
@endphp
<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация — {{ $event?->title ?? 'Платформа мероприятий' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .yandex-form-container {
            width: 100%;
        }
        .yandex-form-container iframe,
        .yandex-form-container form {
            width: 100%;
        }
    </style>
</head>
<body class="bg-surface text-text">

{{-- Полоса загрузки --}}
<div id="page-progress-bar"></div>

<nav id="main-navbar" class="fixed inset-x-0 top-0 z-50 transition-all duration-300 text-black bg-white" x-data="{ menuOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5">
        <a href="{{ url('/') }}" class="flex items-center gap-3 font-bold uppercase tracking-wide cursor-pointer text-black">
            @if($event?->logo)
                <img src="{{ asset('storage/'.$event->logo) }}" class="h-10 w-10 rounded-full object-cover" alt="Logo">
            @endif
            <span class="text-sm md:block hidden">{{ $event?->title ?? 'Fifth Event' }}</span>
        </a>
        <div class="hidden items-center gap-6 text-sm font-medium md:flex">
            <a href="{{ url('/') }}#speakers" class="hover:text-[var(--color-primary)] transition-colors">СПИКЕРЫ</a>
            <a href="{{ url('/') }}#keynote" class="hover:text-[var(--color-primary)] transition-colors">ГОСТИ</a>
            <a href="{{ url('/') }}#schedule" class="hover:text-[var(--color-primary)] transition-colors">РАСПИСАНИЕ</a>
            <a href="{{ url('/') }}#documents" class="hover:text-[var(--color-primary)] transition-colors">ДОКУМЕНТЫ</a>
            <a href="{{ url('/') }}#faq" class="hover:text-[var(--color-primary)] transition-colors">FAQ</a>
            <a href="{{ url('/') }}#venue" class="hover:text-[var(--color-primary)] transition-colors">АДРЕС</a>
        </div>
        <button id="menuToggle" class="md:hidden flex items-center gap-2 text-black border border-[var(--color-border)] p-2 hover:bg-[var(--color-background)] hover:text-[var(--color-primary)] transition-colors rounded-[var(--radius-btn)]" @click="menuOpen=!menuOpen">
            <span x-text="menuOpen ? '✕' : '☰'" class="text-xl font-bold"></span>
        </button>
    </div>
    <div id="mobileMenu" x-show="menuOpen" x-transition
         class="absolute top-full left-0 right-0 bg-white/95 backdrop-blur-md md:hidden">
        <div class="flex flex-col items-center gap-2 px-6 py-6">
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="{{ url('/') }}#speakers" @click="menuOpen=false">СПИКЕРЫ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="{{ url('/') }}#keynote" @click="menuOpen=false">ГОСТИ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="{{ url('/') }}#schedule" @click="menuOpen=false">РАСПИСАНИЕ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="{{ url('/') }}#documents" @click="menuOpen=false">ДОКУМЕНТЫ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="{{ url('/') }}#faq" @click="menuOpen=false">FAQ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="{{ url('/') }}#venue" @click="menuOpen=false">АДРЕС</a>
        </div>
    </div>
</nav>

<div class="min-h-screen pt-32 pb-12">
    <div class="mx-auto max-w-4xl px-6">
        <div class="mb-12 text-center">
            <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Регистрация</p>
            <h1 class="mt-3 text-2xl md:text-3xl font-bold text-[var(--color-text)] leading-tight">{{ $event?->title ?? 'Регистрация на мероприятие' }}</h1>
        </div>

        @if(!$event)
            <div class="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-background)] p-8 text-center text-[var(--color-muted)]">
                Активное мероприятие не найдено.
            </div>
        @elseif(!$event->is_registration_open)
            <div class="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-background)] p-8 text-center text-[var(--color-muted)]">
                Регистрация для этого мероприятия пока не открыта.
            </div>
        @else
            <div class="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-8 text-[var(--color-text)]">
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
                <a href="{{ url('/') }}" class="flex items-center gap-3 text-xs font-semibold uppercase tracking-wide">
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

            {{-- Навигация и соцсети --}}
            <div>
                <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-4">Навигация</p>
                <div class="space-y-2 text-sm">
                    <a href="{{ url('/') }}" class="block text-gray-300 hover:text-white transition-colors">Главная</a>
                    <a href="{{ route('archive') }}" class="block text-gray-300 hover:text-white transition-colors">Архив мероприятий</a>
                    @if($event && $event->show_privacy_section)
                        @if($event->privacy_policy)
                            <a href="#privacy-policy" class="block text-gray-300 hover:text-white transition-colors">Политика конфиденциальности</a>
                        @endif
                        @if($event->personal_data_consent)
                            <a href="#personal-data-consent" class="block text-gray-300 hover:text-white transition-colors">Обработка персональных данных</a>
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

{{-- Офлайн-уведомление --}}
<div id="offline-notification" class="fixed inset-x-0 bottom-0 z-50 hidden bg-[var(--color-primary)] text-white p-4 text-center font-medium" x-data="{ offline: !navigator.onLine }" x-show="offline" x-transition>
    <div class="mx-auto max-w-7xl flex items-center justify-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
        </svg>
        <span>Нет подключения к интернету. Некоторые функции могут быть недоступны.</span>
    </div>
</div>

@livewireScripts
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

    // Отслеживание статуса подключения к интернету
    const offlineNotification = document.getElementById('offline-notification');

    window.addEventListener('online', () => {
        if (offlineNotification) {
            offlineNotification.classList.add('hidden');
        }
    });

    window.addEventListener('offline', () => {
        if (offlineNotification) {
            offlineNotification.classList.remove('hidden');
        }
    });

    // Проверка статуса при загрузке
    if (!navigator.onLine) {
        if (offlineNotification) {
            offlineNotification.classList.remove('hidden');
        }
    }
</script>
</body>
</html>
