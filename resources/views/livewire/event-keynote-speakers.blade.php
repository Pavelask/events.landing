@php
use Illuminate\Support\Facades\Storage;
@endphp
<section id="keynote" class="keynote-section py-20 text-[var(--color-text)] bg-[var(--color-background)]">
    <div class="mx-auto max-w-6xl px-4">
        <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Приглашённые гости</p>
        <h2 class="mt-3 text-4xl font-bold text-[var(--color-text)]">VIP-гости</h2>

        <div class="mt-12 grid grid-cols-2 gap-8 md:grid-cols-4">
        @forelse ($guests as $guest)
            <div class="keynote-card rounded-[var(--radius-card)] border border-[var(--color-border)] bg-white p-4 text-center shadow-sm hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all">
                <img
                    src="{{ $guest->photo_url ?? Storage::url('img/Simpleicons_Interface_user-black-close-up-shape.svg.png') }}"
                    alt="{{ $guest->name }}"
                    class="mx-auto h-32 w-32 rounded-[var(--radius-round)] object-cover shadow-md"
                />
                <h3 class="mt-4 font-semibold text-[var(--color-text)]">{{ $guest->name }}</h3>
                @if ($guest->position)
                    <p class="text-sm text-[var(--color-text-secondary)]">{{ $guest->position }}</p>
                @endif
                @if ($guest->organization)
                    <p class="text-sm text-[var(--color-muted)]">{{ $guest->organization }}</p>
                @endif
                @if ($guest->description)
                    <div class="mt-2 text-xs text-[var(--color-muted)] text-left">{!! $guest->description !!}</div>
                @endif
                <span class="mt-3 inline-block rounded-[var(--radius-btn)] bg-[var(--color-vip)] px-3 py-1 text-xs font-semibold text-white">VIP</span>
            </div>
        @empty
            <p class="col-span-full text-center text-[var(--color-muted)]">Приглашённые гости пока не добавлены.</p>
        @endforelse
        </div>
    </div>
</section>
