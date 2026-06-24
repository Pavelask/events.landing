{{-- Hero-секция: полноэкранный слайдер с параллаксом, автоплеем (3 сек) --}}
{{-- Фоновая картинка 100% площади, параллакс справа налево --}}
{{-- Последовательность: картинка → заголовок → подзаголовок → кнопка --}}
@php
use Illuminate\Support\Facades\Storage;
@endphp
<section id="hero" class="relative min-h-screen overflow-hidden bg-white text-[var(--color-text)]">
    @if($event)
        {{-- Основной слайдер --}}
        <div class="swiper hero-swiper h-screen">
            <div class="swiper-wrapper">
                @forelse($slides as $slide)
                    {{-- Слайд: контент прижат к левому нижнему углу --}}
                    <div class="swiper-slide relative h-screen"
                         style="background-color: {{ $slide->background_color ?? '#f7f7f7' }}">
                        @if($slide->image)
                            {{-- Фоновое изображение 100% площади с параллаксом справа налево --}}
                            <div class="absolute inset-0 bg-cover bg-center"
                                 style="background-image:url('{{ Storage::url($slide->image) }}');"
                                 data-swiper-parallax-x="-25%"></div>
                        @endif
                        {{-- Градиент-затемнение поверх изображения --}}
                        <div class="absolute inset-0 bg-black/40"></div>
                        {{-- Блок контента: левый нижний угол, отступ снизу для пагинации --}}
                        <div class="absolute bottom-16 left-0 z-10 w-full px-6 md:bottom-24">
                            <div class="mx-auto max-w-7xl">
                                {{-- Заголовок — появляется вторым --}}
                                <h1 class="max-w-4xl text-xl font-bold uppercase leading-tight md:text-3xl lg:text-3xl text-white"
                                    data-swiper-parallax-x="-400"
                                    data-swiper-parallax-duration="600"
                                    data-swiper-parallax-opacity="0">
                                    {!! clean_html($slide->title ?: $event->title) !!}
                                </h1>
                                {{-- Подзаголовок — появляется третьим --}}
                                <div class="mt-4 max-w-2xl text-base text-white/70 md:text-lg lg:text-xl [&>p]:text-white/90"
                                   data-swiper-parallax-x="-300"
                                   data-swiper-parallax-duration="1800"
                                   data-swiper-parallax-opacity="0">
                                    {!! clean_html($slide->subtitle ?: $event->description) !!}
                                </div>
                                {{-- Кнопка — появляется последней --}}
                                @if($slide->is_button_visible)
                                    <a href="{{ $slide->button_url ?: '#' }}"
                                       class="mt-6 inline-flex rounded-[var(--radius-btn)] bg-[var(--color-primary)] px-8 py-4 font-semibold text-white transition hover:bg-[var(--color-primary-hover)]"
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
                    <div class="swiper-slide relative h-screen bg-gradient-to-br from-[var(--color-text)] via-gray-800 to-gray-600">
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-text)]/80 via-black/40 to-transparent"></div>
                        <div class="absolute bottom-16 left-0 z-10 w-full px-6 md:bottom-24">
                            <div class="mx-auto max-w-7xl">
                                <h1 class="max-w-4xl text-xl font-bold uppercase leading-tight md:text-3xl lg:text-3xl text-white">{!! clean_html($event->title) !!}</h1>
                                <div class="mt-4 max-w-2xl text-base text-white/90 md:text-lg lg:text-xl [&>p]:text-white/90">{!! clean_html($event->description) !!}</div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Пагинация (точки) - цвет primary --}}
            <div class="swiper-pagination !text-[var(--color-primary)]"></div>
            {{-- Стрелки навигации — иконки --}}
            <div class="swiper-button-prev !text-white">
                <x-heroicon-o-chevron-left class="h-6 w-6" />
            </div>
            <div class="swiper-button-next !text-white">
                <x-heroicon-o-chevron-right class="h-6 w-6" />
            </div>
        </div>
    @else
        {{-- Заглушка, если нет активного мероприятия --}}
        <div class="flex min-h-screen items-center justify-center px-6 text-center">
            <div>
                <p class="text-[var(--color-primary)]">События ещё не опубликованы</p>
                <h1 class="mt-3 text-5xl font-bold text-[var(--color-text)]">Платформа мероприятий</h1>
            </div>
        </div>
    @endif
</section>

