@php
    $startDate = $activeEvent && $activeEvent->start_date->isFuture() ? $activeEvent->start_date->toIso8601String() : null;
@endphp
<!doctype html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $activeEvent?->title ?? 'Платформа мероприятий' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>.navbar-scrolled {
            background: rgba(9, 9, 11, .92);
            backdrop-filter: blur(18px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, .25)
        }

        .fifth-toast {
            position: fixed;
            inset: auto 1rem 1rem auto;
            z-index: 80;
            border-radius: 999px;
            background: #f59e0b;
            color: #000;
            padding: .75rem 1.25rem;
            font-weight: 900
        }

        @media (max-width: 768px) {
            .ya-form-iframe {
                height: 500px !important;
                min-height: 500px !important;
            }
        }</style>
</head>
<body class="bg-zinc-950 text-white">

<!-- Пасхалка Лилу Даллас — 5 кликов по картинке в секции О мероприятии -->
<div id="easter-egg" style="display:none; position:fixed; inset:0; z-index:100; align-items:center; justify-content:center; pointer-events:none;">
    <div style="text-align:center;">
        <img src="{{ asset('storage/img/Leeloo_with_multipass.webp') }}" alt="Leeloo" style="width:300px; height:auto; margin-bottom:16px; border-radius:16px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.5);">
        <div style="font-size:36px; font-weight:900; background:#fbbf24; color:#000; padding:16px 32px; border-radius:9999px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
            Leeloo Dallas Multipass
        </div>
    </div>
</div>

<script>
    (function() {
        let posterClicks = 0;
        let lastClickTime = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const poster = document.querySelector('#about img[alt="{{ $activeEvent?->title ?? 'event' }}"]');
            if (!poster) return;

            poster.addEventListener('click', function(e) {
                const now = Date.now();
                if (now - lastClickTime > 2000) {
                    posterClicks = 0;
                }
                lastClickTime = now;
                posterClicks++;

                if (posterClicks >= 5) {
                    posterClicks = 0;
                    const egg = document.getElementById('easter-egg');
                    egg.style.display = 'flex';
                    setTimeout(() => egg.style.display = 'none', 2000);
                }
            });
        });
    })();
</script>

<nav id="main-navbar" class="fixed inset-x-0 top-0 z-50 transition-all duration-300" x-data="{ menuOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4"><a href="#hero"
                                                                                  class="flex items-center gap-3 font-black uppercase tracking-widest cursor-pointer">
            @if($activeEvent?->logo)
                <img src="{{ asset('storage/'.$activeEvent->logo) }}" class="h-10 w-10 rounded-full object-cover"
                     alt="Logo">
            @endif<span>{{ $activeEvent?->title ?? 'Fifth Event' }}</span></a>
        <div class="hidden items-center gap-6 text-sm font-semibold md:flex"><a href="#about">О событии</a><a
                href="#speakers">Спикеры</a><a href="#keynote">Keynote</a><a href="#schedule">Расписание</a><a
                href="#documents">Документы</a><a href="#faq">FAQ</a><a href="#gallery">Галерея</a><a href="#venue">Площадка</a><a href="#archive">Архив</a>
        </div>
        <button class="md:hidden flex items-center gap-2" @click="menuOpen=!menuOpen">
            <span x-text="menuOpen ? '✕' : '☰'"></span>
        </button>
    </div>
    <div x-show="menuOpen" x-transition
         class="absolute top-full left-0 right-0 bg-black/95 backdrop-blur-md border-t border-white/10 md:hidden">
        <div class="flex flex-col items-center gap-4 px-6 py-6">
            <a class="block py-2 text-base" href="#about" @click="menuOpen=false">О событии</a>
            <a class="block py-2 text-base" href="#speakers" @click="menuOpen=false">Спикеры</a>
            <a class="block py-2 text-base" href="#keynote" @click="menuOpen=false">Keynote</a>
            <a class="block py-2 text-base" href="#schedule" @click="menuOpen=false">Расписание</a>
            <a class="block py-2 text-base" href="#documents" @click="menuOpen=false">Документы</a>
            <a class="block py-2 text-base" href="#faq" @click="menuOpen=false">FAQ</a>
            <a class="block py-2 text-base" href="#gallery" @click="menuOpen=false">Галерея</a>
            <a class="block py-2 text-base" href="#venue" @click="menuOpen=false">Площадка</a>
            <a class="block py-2 text-base" href="#archive" @click="menuOpen=false">Архив</a>
        </div>
    </div>
</nav>
<livewire:event-hero :event="$activeEvent"/>
@if($activeEvent)
    <section id="about" class="about-event bg-white py-20 text-zinc-950">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-2">
            <div><p class="font-bold uppercase tracking-widest text-orange-500">О мероприятии</p>
                <h2 class="mt-3 text-4xl font-black">{{ $activeEvent->title }}</h2>
                <p class="mt-6 text-lg text-zinc-600">{!! $activeEvent->description !!}</p>
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-zinc-100 p-6">
                        <div class="text-sm text-zinc-500">Даты</div>
                        <div class="mt-1 font-black">{{ $activeEvent->start_date->format('d.m.Y') }}
                            - {{ $activeEvent->end_date->format('d.m.Y') }}</div>
                    </div>
                    <div class="rounded-3xl bg-zinc-100 p-6">
                        <div class="text-sm text-zinc-500">Дней</div>
                        <div class="mt-1 font-black">{{ $activeEvent->duration_days }}</div>
                    </div>
                </div>
                @if($activeEvent && $activeEvent->is_registration_open)
                    <a href="{{ route('registration') }}"
                       class="mt-6 inline-block rounded-full bg-amber-400 px-8 py-3 font-black text-black hover:bg-amber-500 transition-colors">
                        Регистрация
                    </a>
                @endif

                {{-- Счётчик времени до начала мероприятия --}}
                @if($startDate)
                    <div id="countdown" data-start="{{ $startDate }}" class="mt-8">
                        <p class="text-sm font-semibold uppercase tracking-widest text-orange-500 text-center md:text-left">До старта осталось</p>
                        <div class="mt-4 grid grid-cols-2 gap-3 md:gap-4 md:grid-cols-4">
                            @foreach(['days'=>'дней','hours'=>'часов','minutes'=>'минут','seconds'=>'секунд'] as $key=>$label)
                                <div class="rounded-2xl border border-orange-200 bg-orange-50 p-4 md:p-6">
                                    <div id="countdown-{{ $key }}" class="countdown-value text-3xl font-black text-orange-500 md:text-5xl">00</div>
                                    <div class="mt-1 text-xs uppercase tracking-widest text-zinc-500 md:mt-2 md:text-sm">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div>@if($activeEvent->video_url)
                    <div x-init="new Plyr($refs.player)" class="overflow-hidden rounded-[2rem] bg-black">
                        <video x-ref="player" controls playsinline>
                            <source src="{{ $activeEvent->video_url }}">
                        </video>
                    </div>
                @elseif($activeEvent->poster_image)
                    <img src="{{ asset('storage/'.$activeEvent->poster_image) }}" class="rounded-[2rem] object-cover cursor-pointer"
                         alt="{{ $activeEvent->title }}">
                @endif</div>
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
    <section id="faq" class="faq-section bg-white py-16 text-zinc-950">
        <div class="mx-auto max-w-4xl px-4"><h2 class="mb-8 text-center text-3xl font-bold">Часто задаваемые
                вопросы</h2>
            <div class="space-y-4">@foreach($activeEvent->faqs as $faq)
                    <div class="faq-item rounded-2xl bg-zinc-100 p-5" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex w-full items-center justify-between text-left font-semibold">{{ $faq->question }}
                            <span :class="open ? 'rotate-180' : ''" class="transition-transform">▼</span></button>
                        <div x-show="open" x-transition class="mt-2 text-gray-600">{!! $faq->answer !!}</div>
                    </div>
                @endforeach</div>
        </div>
    </section>
@endif

@if($activeEvent && $activeEvent->gallery && is_array($activeEvent->gallery) && count($activeEvent->gallery) > 0)
    <section id="gallery" class="gallery-fullscreen bg-zinc-950 py-20 text-white">
        <div class="mx-auto px-4">
            <h2 class="mb-12 text-center text-3xl font-bold gallery-title">Галерея мероприятия</h2>

            {{-- Masonry Gallery --}}
            <div class="gallery-masonry w-full columns-1 gap-4 md:columns-2 lg:columns-3 xl:columns-4">
                @foreach($activeEvent->gallery as $image)
                    @if($image)
                        <div class="mb-4 break-inside-avoid gallery-item overflow-hidden rounded-2xl">
                            <img
                                src="{{ asset('storage/'.$image) }}"
                                alt="{{ $activeEvent->title }}"
                                class="w-full object-cover shadow-lg gallery-image cursor-pointer"
                                loading="lazy"
                            />
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif

@if($activeEvent)
    <section id="venue" class="bg-zinc-950 py-20 text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-2">
            <div><p class="font-bold uppercase tracking-widest text-cyan-300">Площадка</p>
                <h2 class="mt-3 text-4xl font-black">{{ $activeEvent->venue_name }}</h2>
                <p class="mt-4 text-zinc-300">{{ $activeEvent->venue_address }}</p>
                <p class="mt-6 text-zinc-400">{!! $activeEvent->venue_how_to_get !!}</p>
            </div>@if($activeEvent->venue_lat && $activeEvent->venue_lng)
                <iframe class="h-96 w-full rounded-[2rem]"
                        src="https://yandex.ru/map-widget/v1/?ll={{ $activeEvent->venue_lng }}%2C{{ $activeEvent->venue_lat }}&z=15&pt={{ $activeEvent->venue_lng }},{{ $activeEvent->venue_lat }},pm2rdm"
                        loading="lazy"></iframe>
            @endif</div>
    </section>
@endif
<livewire:event-archive/>
<button id="scrollTop" x-data="{
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
        class="fixed bottom-6 right-6 z-50 rounded-full bg-amber-400 p-4 font-black text-black shadow-2xl">↑
</button>
@vite(['resources/js/app.js'])
@livewireScripts
</body>
</html>
