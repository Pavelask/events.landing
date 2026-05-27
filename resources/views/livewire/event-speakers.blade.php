@php
use Illuminate\Support\Facades\Storage;
@endphp
<section id="speakers" class="speakers-grid mx-auto max-w-6xl px-4 py-20 text-zinc-950 bg-white">
    <h2 class="mb-12 text-center text-3xl font-bold text-gray-900">Спикеры</h2>

    <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
        @forelse ($speakers as $speaker)
            <div class="speaker-card text-center">
                @if($speaker->photo_url)
                    <img
                        src="{{ $speaker->photo_url }}"
                        alt="{{ $speaker->name }}"
                        class="mx-auto w-full aspect-[3/4] rounded-lg object-cover shadow-lg"
                    />
                @else
                    <img
                        src="{{ Storage::url('img/Simpleicons_Interface_user-black-close-up-shape.svg.png') }}"
                        alt="{{ $speaker->name }}"
                        class="mx-auto w-full aspect-[3/4] rounded-lg object-cover bg-white p-2 border-4 border-white"
                    />
                @endif
                <h3 class="mt-4 font-semibold">{{ $speaker->name }}</h3>
                @if ($speaker->position)
                    <p class="text-sm text-gray-600">{{ $speaker->position }}</p>
                @endif
                @if ($speaker->organization)
                    <p class="text-sm text-gray-500">{{ $speaker->organization }}</p>
                @endif
                @if ($speaker->description)
                    <div class="mt-3 text-xs text-gray-500 text-left">{!! $speaker->description !!}</div>
                @endif
            </div>
        @empty
            <p class="col-span-full text-center text-gray-400">Спикеры пока не добавлены.</p>
        @endforelse
    </div>
</section>
