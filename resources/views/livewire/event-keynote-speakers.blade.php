@php
use Illuminate\Support\Facades\Storage;
@endphp
<section id="keynote" class="keynote-grid mx-auto max-w-6xl px-4 py-20 text-zinc-950 bg-white">
    <h2 class="mb-12 text-center text-3xl font-bold text-gray-900">Приглашённые гости</h2>

    <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
        @forelse ($guests as $guest)
            <div class="keynote-card rounded-2xl border-2 border-yellow-400 bg-white p-4 text-center shadow-lg">
                <img
                    src="{{ $guest->photo_url ?? Storage::url('img/Simpleicons_Interface_user-black-close-up-shape.svg.png') }}"
                    alt="{{ $guest->name }}"
                    class="mx-auto h-32 w-32 rounded-full object-cover shadow-lg"
                />
                <h3 class="mt-4 font-semibold">{{ $guest->name }}</h3>
                @if ($guest->position)
                    <p class="text-sm text-gray-500">{{ $guest->position }}</p>
                @endif
                @if ($guest->organization)
                    <p class="text-sm text-gray-400">{{ $guest->organization }}</p>
                @endif
                @if ($guest->description)
                    <div class="mt-2 text-xs text-gray-500 text-left">{!! $guest->description !!}</div>
                @endif
                <span class="mt-2 inline-block rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700">VIP</span>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-400">Приглашённые гости пока не добавлены.</p>
        @endforelse
    </div>
</section>
