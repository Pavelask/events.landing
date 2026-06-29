@php
use Illuminate\Support\Facades\Storage;
@endphp
<section id="speakers" class="speakers-grid mx-auto max-w-6xl px-4 py-20 text-[var(--color-text)] bg-white">
    <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Спикеры</p>
    <!-- <h2 class="mt-3 text-4xl font-bold text-[var(--color-text)]">Наши спикеры</h2> -->

    <div class="mt-12 grid grid-cols-2 gap-8 md:grid-cols-4">
        @forelse ($speakers as $eventSpeaker)
            @php $speaker = $eventSpeaker->speaker; @endphp
            @if ($speaker)
            <div class="speaker-card rounded-[var(--radius-card)] border {{ $eventSpeaker->is_keynote ? 'border-2 border-[var(--color-primary)] bg-slate-50' : 'border-[var(--color-border)] bg-white' }} p-4 text-center shadow-sm hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all">
                @if($speaker->photo_url)
                    <img
                        src="{{ $speaker->photo_url }}"
                        alt="{{ $speaker->name }}"
                        loading="lazy" decoding="async"
                        class="mx-auto h-32 w-32 rounded-[var(--radius-round)] object-cover shadow-md {{ $eventSpeaker->is_keynote ? 'ring-2 ring-[var(--color-primary)] ring-offset-2' : '' }}"
                    />
                @else
                    <img
                        src="{{ Storage::url('img/Simpleicons_Interface_user-black-close-up-shape.svg.png') }}"
                        alt="{{ $speaker->name }}"
                        loading="lazy" decoding="async"
                        class="mx-auto h-32 w-32 rounded-[var(--radius-round)] object-cover bg-white p-2 border border-[var(--color-border)]"
                    />
                @endif
                <h3 class="mt-4 font-semibold text-[var(--color-text)]">{{ $speaker->name }}</h3>
                @if ($speaker->position)
                    <p class="text-sm text-[var(--color-text-secondary)]">{{ $speaker->position }}</p>
                @endif
                @if ($speaker->organization)
                    <p class="text-sm text-[var(--color-muted)]">{{ $speaker->organization }}</p>
                @endif
                @if ($speaker->description)
                    <div class="mt-2 text-xs text-[var(--color-muted)] text-left hidden md:block">{!! clean_html($speaker->description) !!}</div>
                @endif
            </div>
            @endif
        @empty
            <p class="col-span-full text-center text-[var(--color-muted)]">Спикеры пока не добавлены.</p>
        @endforelse
    </div>
</section>
