@php
use Illuminate\Support\Facades\Storage;
@endphp
@if($event && $event->gallery && is_array($event->gallery) && count($event->gallery) > 0)
    <section id="gallery" class="bg-zinc-950 py-20 text-white">
        <div class="mx-auto px-4">
            <!-- <h2 class="mb-12 text-center text-3xl font-bold">Галерея мероприятия</h2> -->

            {{-- Masonry Gallery --}}
            <div class="gallery-masonry columns-1 gap-4 md:columns-2 lg:columns-3 xl:columns-4">
                @foreach($event->gallery as $image)
                    @if($image)
                        <div class="mb-4 break-inside-avoid">
                            <img
                                src="{{ Storage::url($image) }}"
                                alt="{{ $event->title }}"
                                class="w-full rounded-2xl object-cover shadow-lg transition-transform hover:scale-105"
                                loading="lazy"
                            />
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif