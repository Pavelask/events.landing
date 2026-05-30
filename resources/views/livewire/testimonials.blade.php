@php
@endphp

<section id="testimonials" class="bg-[var(--color-background)] py-20 text-[var(--color-text)]">
    <div class="mx-auto max-w-6xl px-4">
        <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Отзывы участников</p>
        <h2 class="mt-3 text-4xl font-bold text-[var(--color-text)]">Что говорят о нас</h2>

        @if ($testimonials->isEmpty())
            <p class="mt-6 text-[var(--color-text-secondary)]">Пока нет отзывов.</p>
        @else
            <div class="mt-12 grid gap-6">
                @foreach ($testimonials as $testimonial)
                    <div class="testimonial-card rounded-[var(--radius-card)] border border-[var(--color-border)] bg-white p-4 shadow-sm hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all flex items-center gap-4">
                        <div class="flex h-28 w-28 items-center justify-center rounded-[var(--radius-round)] bg-[var(--color-text)] text-white shadow-md flex-shrink-0 overflow-hidden">
                            @if ($testimonial->photo_url)
                                <img
                                    src="{{ $testimonial->photo_url }}"
                                    alt="{{ $testimonial->author_name }}"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <span class="text-4xl font-bold">{{ strtoupper(substr($testimonial->author_name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-lg font-semibold text-[var(--color-text)]">{{ $testimonial->author_name }}</div>
                            <div class="mt-2 text-sm text-[var(--color-text-secondary)] leading-relaxed line-clamp-4">
                                {!! $testimonial->content !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
