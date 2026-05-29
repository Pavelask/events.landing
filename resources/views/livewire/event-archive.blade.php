<div id="archive" class="bg-[var(--color-background)] py-20">
    @if ($lastCompletedEvent)
        <div class="mx-auto max-w-7xl px-6">
            <div class="archive-banner relative flex h-[450px] items-center justify-center rounded-[var(--radius-card)] bg-cover bg-center overflow-hidden"
                style="background-image: url('{{ $lastCompletedEvent->poster_image ? Storage::url($lastCompletedEvent->poster_image) : ($lastCompletedEvent->heroSlides->first()?->image ? Storage::url($lastCompletedEvent->heroSlides->first()->image) : '') }}');">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="relative z-10 px-4 text-center text-white">
                    @if ($lastCompletedEvent->logo)
                        <img src="{{ Storage::url($lastCompletedEvent->logo) }}" alt="logo" class="mx-auto mb-4 max-h-20 rounded-[var(--radius-round)]">
                    @endif
                    <h2 class="mb-2 text-4xl font-bold">{{ $lastCompletedEvent->title }}</h2>
                    <p class="mb-2 text-xl">{{ $lastCompletedEvent->start_date->format('d M Y') }} - {{ $lastCompletedEvent->end_date->format('d M Y') }}</p>
                    <p class="mb-6 text-gray-200">{{ Str::limit($lastCompletedEvent->description, 100) }}</p>
                    <a href="{{ route('event.show', $lastCompletedEvent->slug) }}" class="inline-block rounded-[var(--radius-btn)] border border-white px-6 py-2 transition hover:bg-white hover:text-black">
                        Подробнее
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
