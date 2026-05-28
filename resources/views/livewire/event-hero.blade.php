{{-- Hero-секция: полноэкранный слайдер с параллаксом, автоплеем (3 сек) --}}
{{-- Фоновая картинка 100% площади, параллакс справа налево --}}
{{-- Последовательность: картинка → заголовок → подзаголовок → кнопка --}}
@php
use Illuminate\Support\Facades\Storage;
@endphp
<section id="hero" class="relative min-h-screen overflow-hidden bg-black text-white">
    @if($event)
        {{-- Основной слайдер --}}
        <div class="swiper hero-swiper h-screen">
            <div class="swiper-wrapper">
                @forelse($slides as $slide)
                    {{-- Слайд: контент прижат к левому нижнему углу --}}
                    <div class="swiper-slide relative h-screen"
                         style="background-color: {{ $slide->background_color ?? '#0f172a' }}">
                        @if($slide->image)
                            {{-- Фоновое изображение 100% площади с параллаксом справа налево --}}
                            <div class="absolute inset-0 bg-center"
                                 style="background-image:url('{{ Storage::url($slide->image) }}'); background-size: 130% 130%;"
                                 data-swiper-parallax-x="-25%"></div>
                        @endif
                        {{-- Градиент-затемнение поверх изображения --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        {{-- Блок контента: левый нижний угол, отступ снизу для пагинации --}}
                        <div class="absolute bottom-16 left-0 z-10 w-full px-6 md:bottom-24">
                            <div class="mx-auto max-w-7xl">
                                {{-- Заголовок — появляется вторым --}}
                                <h1 class="max-w-4xl text-4xl font-black uppercase leading-tight md:text-6xl lg:text-7xl"
                                    data-swiper-parallax-x="-400"
                                    data-swiper-parallax-duration="600"
                                    data-swiper-parallax-opacity="0">
                                    {{ $slide->title ?: $event->title }}
                                </h1>
                                {{-- Подзаголовок — появляется третьим --}}
                                <p class="mt-4 max-w-2xl text-lg text-zinc-200 md:text-xl lg:text-2xl"
                                   data-swiper-parallax-x="-300"
                                   data-swiper-parallax-duration="1800"
                                   data-swiper-parallax-opacity="0">
                                    {{ $slide->subtitle ?: $event->description }}
                                </p>
                                {{-- Кнопка — появляется последней --}}
                                @if($slide->is_button_visible)
                                    <a href="{{ $slide->button_url ?: '#' }}"
                                       class="mt-6 inline-flex rounded-full bg-white px-8 py-4 font-bold text-black transition hover:bg-gray-200"
                                       data-swiper-parallax-x="-200"
                                       data-swiper-parallax-duration="2000"
                                       data-swiper-parallax-opacity="0">
                                        {{ $slide->button_text ?: 'Подробнее' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Fallback-слайд, если слайдов нет --}}
                    <div class="swiper-slide relative h-screen bg-gradient-to-br from-black via-zinc-800 to-zinc-600">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        <div class="absolute bottom-16 left-0 z-10 w-full px-6 md:bottom-24">
                            <div class="mx-auto max-w-7xl">
                                <h1 class="max-w-4xl text-4xl font-black uppercase leading-tight md:text-6xl lg:text-7xl">{{ $event->title }}</h1>
                                <p class="mt-4 max-w-2xl text-lg text-white/90 md:text-xl lg:text-2xl">{{ $event->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Пагинация (точки) - белый цвет --}}
            <div class="swiper-pagination !text-white"></div>
            {{-- Стрелки навигации --}}
            <div class="swiper-button-prev !text-white"></div>
            <div class="swiper-button-next !text-white"></div>
        </div>
    @else
        {{-- Заглушка, если нет активного мероприятия --}}
        <div class="flex min-h-screen items-center justify-center px-6 text-center">
            <div>
                <p class="text-amber-300">События ещё не опубликованы</p>
                <h1 class="mt-3 text-5xl font-black">Платформа мероприятий</h1>
            </div>
        </div>
    @endif
</section>

