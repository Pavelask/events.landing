@php
    $startDate = $activeEvent && $activeEvent->start_date->isFuture() ? $activeEvent->start_date->toIso8601String() : null;
@endphp
<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $activeEvent?->title ?? 'Платформа мероприятий' }}</title>
    <link rel="icon" type="image/png" href="{{ $activeEvent ? route('event.favicon', $activeEvent) : asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ $activeEvent ? route('event.apple-touch-icon', $activeEvent) : asset('favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if($activeEvent)
        <meta name="event-slug" content="{{ $activeEvent->slug }}">
    @endif
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-text">

{{-- Полоса загрузки --}}
<div id="page-progress-bar"></div>

<!-- Пасхалка Лилу Даллас — 5 кликов по картинке в секции О мероприятии -->
<div id="easter-egg" style="display:none; position:fixed; inset:0; z-index:100; align-items:center; justify-content:center; pointer-events:none;">
    <div id="easter-egg-backdrop" style="position:absolute; inset:0; background:rgba(0,0,0,0);"></div>
    <div id="easter-egg-content" style="text-align:center; position:relative; z-index:1;">
        <img id="easter-egg-img" src="{{ asset('storage/img/Leeloo_with_multipass.webp') }}" alt="Leeloo" style="width:300px; height:auto; margin-bottom:16px; border-radius:16px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.5); opacity:0; transform:scale(0.5) rotate(-10deg);">
        <div id="easter-egg-badge" style="font-size:36px; font-weight:900; background:#fbbf24; color:#000; padding:16px 32px; border-radius:9999px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); opacity:0; transform:translateY(30px);">
            Leeloo Dallas Multipass
        </div>
    </div>
</div>

<nav id="main-navbar" class="fixed inset-x-0 top-0 z-50 transition-all duration-300 text-black bg-white" x-data="{ menuOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5">
        <a href="{{ url('/') }}" class="flex items-center gap-3 font-bold uppercase tracking-wide cursor-pointer text-black navbar-logo">
            @if($activeEvent?->logo)
                <img src="{{ asset('storage/'.$activeEvent->logo) }}" class="h-10 w-10 rounded-full object-cover" alt="Logo">
            @endif
            <span class="text-sm md:block hidden">{{ $activeEvent?->title ?? 'Fifth Event' }}</span>
        </a>
        @if(session('message'))
            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 bg-[var(--color-primary)] text-white px-6 py-2 rounded-[var(--radius-btn)] text-sm font-medium" x-data="{ show: true }" x-show="show" x-transition.duration.3000ms @click="show = false">
                {{ session('message') }}
            </div>
        @endif
        <div class="hidden items-center gap-6 text-sm font-medium md:flex navbar-navlinks">
            <a href="#speakers" class="hover:text-[var(--color-primary)] transition-colors">СПИКЕРЫ</a>
            <a href="#keynote" class="hover:text-[var(--color-primary)] transition-colors">ГОСТИ</a>
            <a href="#schedule" class="hover:text-[var(--color-primary)] transition-colors">РАСПИСАНИЕ</a>
            <a href="#documents" class="hover:text-[var(--color-primary)] transition-colors">ДОКУМЕНТЫ</a>
            <a href="#faq" class="hover:text-[var(--color-primary)] transition-colors">FAQ</a>
            <a href="#venue" class="hover:text-[var(--color-primary)] transition-colors">АДРЕС</a>
        </div>
        <button id="menuToggle" aria-label="Открыть меню" aria-expanded="menuOpen" class="md:hidden flex items-center gap-2 text-black border border-[var(--color-border)] p-2 hover:bg-[var(--color-background)] hover:text-[var(--color-primary)] transition-colors rounded-[var(--radius-btn)] navbar-menu-btn" @click="menuOpen=!menuOpen">
            <span x-text="menuOpen ? '✕' : '☰'" class="text-xl font-bold"></span>
        </button>
    </div>
    <div id="mobileMenu" x-show="menuOpen" x-cloak x-transition
         class="absolute top-full left-0 right-0 bg-white/95 backdrop-blur-md md:hidden">
        <div class="flex flex-col items-center gap-2 px-6 py-6">
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="#speakers" @click="menuOpen=false">СПИКЕРЫ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="#keynote" @click="menuOpen=false">ГОСТИ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="#schedule" @click="menuOpen=false">РАСПИСАНИЕ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="#documents" @click="menuOpen=false">ДОКУМЕНТЫ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="#faq" @click="menuOpen=false">FAQ</a>
            <a class="block py-3 text-base font-medium text-black w-full text-center hover:text-[var(--color-primary)]" href="#venue" @click="menuOpen=false">АДРЕС</a>
        </div>
    </div>
</nav>
<livewire:event-hero :event="$activeEvent"/>
@if($activeEvent)
    <section id="about" class="about-event bg-white py-20 text-[var(--color-text)]">
        <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-2">
            <div>
                <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">О мероприятии</p>
                <h2 class="mt-3 text-2xl md:text-3xl font-bold text-[var(--color-text)] leading-tight">{{ $activeEvent->title }}</h2>
                <p class="mt-8 text-xl md:text-2xl text-[var(--color-text-secondary)] leading-relaxed">{{ strip_tags($activeEvent->description) }}</p>
                <div class="mt-10 grid gap-6 sm:grid-cols-6">
                    <div class="event-card p-8 sm:col-span-5 text-center">
                        <div class="text-xs font-semibold uppercase tracking-wide text-[var(--color-muted)]">Даты проведения мероприятия</div>
                        <div class="mt-3 text-3xl font-bold text-[var(--color-text)]">{{ $activeEvent->start_date->format('d.m.Y') }} — {{ $activeEvent->end_date->format('d.m.Y') }}</div>
                    </div>
                    <div class="event-card py-8 px-4 text-center sm:col-span-1">
                        <div class="text-xs font-semibold uppercase tracking-wide text-[var(--color-muted)]">Дней</div>
                        <div class="mt-2 text-3xl font-bold text-[var(--color-text)]">{{ $activeEvent->duration_days }}</div>
                    </div>
                </div>
                @if($activeEvent && $activeEvent->is_registration_available)
                    <a href="{{ route('registration') }}" class="btn-primary mt-10 block w-full text-center">
                        Зарегистрироваться
                    </a>
                @endif

                {{-- Счётчик времени до начала мероприятия --}}
                @if($startDate)
                    <div id="countdown" data-start="{{ $startDate }}" class="mt-8">
                        <p class="text-xs font-semibold uppercase tracking-wide text-[var(--color-muted)]">До старта осталось</p>
                        <div class="mt-6 grid grid-cols-2 gap-4 md:gap-6 md:grid-cols-4">
                            @foreach(['days'=>'дней','hours'=>'часов','minutes'=>'минут','seconds'=>'секунд'] as $key=>$label)
                                <div class="event-card p-6 md:p-8 text-center">
                                    <div id="countdown-{{ $key }}" class="countdown-value text-4xl md:text-5xl font-bold text-[var(--color-text)]">{{ '00' }}</div>
                                    <div class="mt-2 text-xs font-semibold uppercase tracking-wide text-[var(--color-muted)]">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="event-card overflow-hidden">
                @if($activeEvent->video_url)
                    <div x-init="window.Plyr && new window.Plyr($refs.player) || window.addEventListener('load', () => window.Plyr && new window.Plyr($refs.player))" class="bg-[var(--color-text)]">
                        <video x-ref="player" controls playsinline class="w-full">
                            <source src="{{ $activeEvent->video_url }}">
                        </video>
                    </div>
                @elseif($activeEvent->poster_image)
                    <img src="{{ asset('storage/'.$activeEvent->poster_image) }}" class="w-full object-cover" alt="{{ $activeEvent->title }}" loading="lazy" decoding="async">
                @endif
            </div>
        </div>
    </section>
@endif
@if($activeEvent && $activeEvent->is_media_visible && ($activeEvent->media_image || $activeEvent->media_description))
    <section id="media" class="media-section bg-[var(--color-background)] py-20 text-[var(--color-text)] overflow-hidden">
        <div class="mx-auto max-w-7xl px-6">
            <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Приветствие</p>
            <h2 class="mt-3 text-2xl md:text-3xl font-bold text-[var(--color-text)]">{{ $activeEvent->title }}</h2>
            <div class="mt-10 grid gap-10 lg:grid-cols-3">
                <div class="media-photo-wrapper lg:col-span-1">
                    @if($activeEvent->media_image)
                        <img
                            src="{{ Storage::url($activeEvent->media_image) }}"
                            alt="{{ $activeEvent->title }}"
                            class="media-photo w-full aspect-[3/4] rounded-[var(--radius-card)] object-cover shadow-lg"
                        />
                    @endif
                </div>
                <div class="media-text lg:col-span-2">
                    @if($activeEvent->media_description)
                        <div class="text-base text-[var(--color-text-secondary)] leading-relaxed">
                            {!! clean_html($activeEvent->media_description) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif

<livewire:event-speakers :event="$activeEvent"/>
<livewire:event-keynote-speakers :event="$activeEvent"/>

<livewire:event-schedule :event="$activeEvent"/>

@if($activeEvent && $activeEvent->documents->isNotEmpty())
    <livewire:event-documents :event="$activeEvent"/>
@endif

@if($activeEvent && $activeEvent->faqs->isNotEmpty())
    <section id="faq" class="faq-section bg-white py-20 text-[var(--color-text)]">
        <div class="mx-auto max-w-4xl px-6">
            <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Часто задаваемые вопросы</p>
            <!-- <h2 class="mt-3 text-4xl font-bold text-[var(--color-text)]">FAQ</h2> -->

            <div class="mt-12 space-y-4">
                @foreach($activeEvent->faqs as $faq)
                    <div class="faq-item rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-background)] p-5" x-data="{ open: false }">
                        <button @click="open = !open"
                                :aria-expanded="open"
                                class="flex w-full items-center justify-between text-left font-semibold text-[var(--color-text)]">
                            {{ $faq->question }}
                            <span :class="open ? 'rotate-180' : ''" class="transition-transform rounded-[var(--radius-round)] w-8 h-8 flex items-center justify-center text-[var(--color-muted)]">▼</span>
                        </button>
                        <div x-show="open" x-transition class="mt-3 text-[var(--color-text-secondary)]">{!! clean_html($faq->answer) !!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@if($activeEvent)
    @livewire(\App\Livewire\Testimonials::class, ['event' => $activeEvent])
@endif

@if($activeEvent && $activeEvent->gallery && is_array($activeEvent->gallery) && count(array_filter($activeEvent->gallery)) > 0)
    @php
        $galleryImages = array_values(array_filter(array_map(fn($img) => $img ? asset('storage/'.$img) : null, $activeEvent->gallery)));
    @endphp
    <section id="gallery" class="bg-[#f0f0f0] py-20 text-[var(--color-text)]"
             x-data="{ open: false, index: 0, images: {{ json_encode($galleryImages) }}, next() { this.index = (this.index + 1) % this.images.length }, prev() { this.index = (this.index - 1 + this.images.length) % this.images.length }, close() { this.open = false } }">
        <div class="mx-auto max-w-7xl px-6">
            <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2 text-center">Фотографии с предыдущих мероприятий</p>
            <!-- <h2 class="mt-3 text-center text-4xl font-bold text-[var(--color-text)]">Фотогалерея</h2> -->
            
            <div class="mt-12 columns-2 gap-2 space-y-2 sm:columns-3 md:columns-4 lg:columns-5 xl:columns-6">
                @foreach($galleryImages as $idx => $image)
                    <div class="break-inside-avoid gallery-item overflow-hidden rounded-[var(--radius-card)]">
                        <img
                            src="{{ $image }}"
                            alt="{{ "Фото " . ($idx + 1) . " — " . $activeEvent->title }}"
                            class="w-full object-cover shadow-md gallery-image cursor-pointer brightness-90 hover:brightness-100 transition-all" decoding="async"
                            loading="lazy"
                            @click="index = {{ $idx }}; open = true"
                        />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Lightbox с Swiper для свайпа на мобильных --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] bg-black/90"
            @keydown.window.escape="close()"
            @click.self="close()"
            style="display: none;"
            x-init="$watch('open', val => { if(val) { $nextTick(() => { const el = $refs.lightboxSwiper; if(!el) return; if(el.swiper) { el.swiper.slideTo(index, 0); return; } new Swiper(el, { initialSlide: index, loop: false, zoom: true, on: { slideChange: (s) => { index = s.realIndex; } } }); }); } })"
        >
            <div x-ref="lightboxSwiper" class="swiper w-full h-full lightbox-swiper" @click.stop>
                <div class="swiper-wrapper">
                    <template x-for="(img, i) in images" :key="i">
                        <div class="swiper-slide flex items-center justify-center">
                            <img :src="img" class="max-h-[85vh] max-w-[90vw] object-contain rounded-[var(--radius-card)]" loading="lazy" />
                        </div>
                    </template>
                </div>
            </div>

            {{-- Close --}}
            <button
                @click.stop="close()"
                aria-label="Закрыть"
                class="absolute top-4 right-4 text-white/70 hover:text-white text-3xl transition-colors z-10"
            >&#x2715;</button>

            {{-- Prev / Next — десктоп --}}
            <button
                @click.stop="$refs.lightboxSwiper?.swiper?.slidePrev()"
                aria-label="Предыдущее фото"
                class="hidden md:flex absolute left-2 md:left-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-4xl md:text-5xl font-light transition-colors z-10 items-center justify-center w-12 h-12"
            >&#x2039;</button>
            <button
                @click.stop="$refs.lightboxSwiper?.swiper?.slideNext()"
                aria-label="Следующее фото"
                class="hidden md:flex absolute right-2 md:right-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-4xl md:text-5xl font-light transition-colors z-10 items-center justify-center w-12 h-12"
            >&#x203A;</button>

            {{-- Counter --}}
            <div class="absolute bottom-4 left-0 right-0 text-center text-white/60 text-sm" x-text="(index + 1) + ' / ' + images.length"></div>
        </div>
    </section>
@endif

@if($activeEvent)
    <section id="venue" class="bg-[#e5e5e5] pt-20 pb-20 text-[var(--color-text)]">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-2">
            <div><p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs">Место проведения</p>
                <h2 class="mt-3 text-2xl font-bold">{{ $activeEvent->venue_name }}</h2>
                <p class="mt-4 text-[var(--color-text-secondary)]">{{ $activeEvent->venue_address }}</p>
                <p class="mt-6 text-[var(--color-text-secondary)]">{!! clean_html($activeEvent->venue_how_to_get) !!}</p>
            </div>
            @if($activeEvent->venue_lat && $activeEvent->venue_lng)
                <iframe class="h-96 w-full rounded-[var(--radius-card)] border-2 border-[var(--color-primary)]/30"
                        src="https://yandex.ru/map-widget/v1/?ll={{ $activeEvent->venue_lng }}%2C{{ $activeEvent->venue_lat }}&z=15&pt={{ $activeEvent->venue_lng }},{{ $activeEvent->venue_lat }},pm2rdm"
                        loading="lazy"></iframe>
            @endif
        </div>
    </section>
@endif

{{-- Футтер --}}
<footer class="bg-[var(--color-text)] text-white">
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid gap-10 md:grid-cols-3">
            {{-- Бренд --}}
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-3 text-xs font-semibold uppercase tracking-wide">
                    @if($activeEvent?->logo)
                        <img src="{{ asset('storage/'.$activeEvent->logo) }}" class="h-10 w-10 rounded-full object-cover" alt="Logo" loading="lazy" decoding="async">
                    @endif
                    <span>{{ $activeEvent?->title ?? 'Fifth Event' }}</span>
                </a>
                @if($activeEvent?->description)
                    <p class="mt-4 text-sm text-gray-400">{{ Str::limit(strip_tags($activeEvent->description), 120) }}</p>
                @endif
            </div>

            {{-- Контакты --}}
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

            {{-- Навигация и соцсети --}}
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

                @if($activeEvent?->social_links && is_array($activeEvent->social_links))
                    <x-social-links :socialLinks="$activeEvent->social_links" />
                @endif
            </div>
        </div>

        {{-- Копирайт --}}
        <div class="mt-12 border-t border-white/10 pt-8 text-center text-xs text-gray-500">
            © {{ now()->year }} {{ $activeEvent?->title ?? 'Платформа мероприятий' }}. Все права защищены.
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

<button id="scrollTop" aria-label="Наверх" x-data="{
        visible: false,
        scrollTopClicks: 0,
        handleScroll() { this.visible = window.scrollY > 300 },
        handleClick() {
            this.scrollTopClicks++;
            window.scrollTo({top: 0, behavior: 'smooth'});
            if(this.scrollTopClicks >= 5) {
                const t = document.createElement('div');
                t.className = 'fifth-toast';
                t.textContent = 'Leeloo Dallas Multipass';
                document.body.appendChild(t);
                setTimeout(() => t.remove(), 2000);
                this.scrollTopClicks = 0;
            }
        }
    }" @scroll.window="handleScroll()" x-show="visible"
        x-transition
        @click="handleClick()"
        class="fixed bottom-6 right-6 z-50 rounded-[var(--radius-round)] bg-white text-[var(--color-text)] p-4 font-bold shadow-xl border-2 border-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-white transition-colors w-12 h-12 flex items-center justify-center">↑
</button>
@vite(['resources/js/home.js'])
@livewireScripts

{{-- Баннер о cookie (после Alpine.js инициализации) --}}
@if($activeEvent && $activeEvent->show_cookie_banner && $activeEvent->privacy_cookie_banner_text)
    <div x-data="{
            show: !localStorage.getItem('cookie_consent_accepted_{{ $activeEvent->slug }}') && !localStorage.getItem('cookie_consent_declined_{{ $activeEvent->slug }}')
        }" x-show="show" x-cloak x-transition
         class="fixed inset-x-0 bottom-0 z-50 bg-[var(--color-surface)] border-t border-[var(--color-primary)]/20 shadow-lg">
        <div class="mx-auto max-w-7xl px-4 py-4 flex items-start justify-between gap-4">
            <div class="text-sm text-[var(--color-text)] leading-relaxed">
                <p>{{ $activeEvent->privacy_cookie_banner_text }}</p>
                @if($activeEvent->privacy_cookie_policy)
                    <a href="{{ route('cookie.policy') }}" class="underline hover:text-[var(--color-primary)] transition-colors">{{ $activeEvent->privacy_cookie_banner_title ?: 'Политика использования файлов cookie' }}</a>
                @endif
            </div>
            <div class="flex shrink-0 gap-2">
                <button x-on:click="show = false; localStorage.setItem('cookie_consent_accepted_{{ $activeEvent->slug }}', '1'); localStorage.removeItem('cookie_consent_declined_{{ $activeEvent->slug }}')"
                        class="rounded-lg bg-[var(--color-primary)] px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition-opacity">
                    Принять
                </button>
                <button x-on:click="show = false; localStorage.setItem('cookie_consent_declined_{{ $activeEvent->slug }}', '1'); localStorage.removeItem('cookie_consent_accepted_{{ $activeEvent->slug }}')"
                        class="rounded-lg border border-[var(--color-primary)]/30 px-4 py-2 text-sm font-medium text-[var(--color-text)] hover:bg-[var(--color-primary)]/10 transition-colors">
                    Отклонить
                </button>
            </div>
        </div>
    </div>
@endif

</body>
</html>
