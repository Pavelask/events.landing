<section id="schedule" class="timeline-section mx-auto max-w-4xl px-4 py-20 text-zinc-950">
    <h2 class="mb-12 text-center text-3xl font-bold">Программа мероприятия</h2>

    @if ($event)
        <div class="mb-4 flex justify-end gap-2">
        {{-- <div wire:loading class="mb-2 text-center text-blue-500">Загрузка...</div> --}}

        @if ($selectedDay)
                <a href="{{ route('ical.day', $selectedDay) }}" class="rounded bg-blue-50 px-3 py-1 text-sm text-blue-700 hover:bg-blue-100">Скачать день</a>
            @endif
            <a href="{{ route('ical.full', $event) }}" class="rounded bg-green-50 px-3 py-1 text-sm text-green-700 hover:bg-green-100">Скачать всё</a>
        </div>

        <div class="mb-6 flex gap-2 overflow-x-auto border-b border-gray-200 pb-2">
            @foreach ($days as $day)
                @php
                    $todayDate = \Carbon\Carbon::today()->toDateString();
                    $dayDate = $day->date->toDateString();
                    $isActive = $day->id === $selectedDayId;
                    $isPast = $dayDate < $todayDate;
                    $isToday = $dayDate === $todayDate;
                @endphp
                <button type="button" wire:click="selectDay({{ $day->id }})"
                    class="rounded-t-lg border-b-2 px-4 py-2 text-sm font-medium transition {{ $isActive ? 'border-blue-500 bg-blue-50 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    <span>{{ $day->label }}</span>
                    @if ($isPast && ! $isActive)
                        <span class="ml-1 text-xs text-gray-400">(завершён)</span>
                    @elseif ($isToday && ! $isActive)
                        <span class="ml-1 text-xs text-blue-500">сегодня</span>
                    @endif
                </button>
            @endforeach
        </div>

        @if ($selectedDay)
            @php
                $now = \Carbon\Carbon::now();
            @endphp

            {{-- Debug --}}
            {{-- <div class="mb-4 p-2 bg-yellow-100 text-black">
                Day: {{ $selectedDay->id }} - {{ $selectedDay->label }}
                Events count: {{ $selectedDay->events->count() }}
            </div> --}}

            <div class="relative pl-8 sm:pl-10" wire:key="day-{{ $selectedDay->id }}">
                <div class="absolute bottom-0 left-3 top-2 w-0.5 bg-gray-200 sm:left-4"></div>

                @foreach ($selectedDay->events as $scheduleEvent)
                    @php
                        $start = \Carbon\Carbon::parse($selectedDay->date->toDateString() . ' ' . $scheduleEvent->start_time->format('H:i:s'));
                        $end = $scheduleEvent->end_time
                            ? \Carbon\Carbon::parse($selectedDay->date->toDateString() . ' ' . $scheduleEvent->end_time->format('H:i:s'))
                            : $start->copy()->addHour();

                        if ($now->lt($start)) {
                            $status = 'upcoming';
                        } elseif ($now->between($start, $end)) {
                            $status = 'active';
                        } else {
                            $status = 'past';
                        }

                        $googleUrl = 'https://calendar.google.com/calendar/render?' . http_build_query([
                            'action' => 'TEMPLATE',
                            'text' => $scheduleEvent->title,
                            'dates' => $start->format('Ymd\THis') . '/' . $end->format('Ymd\THis'),
                            'details' => $scheduleEvent->description ?? '',
                            'location' => $scheduleEvent->location ?? '',
                        ]);
                    @endphp

                    <div class="timeline-item relative pb-8 last:pb-0">
                        <div class="absolute left-3 z-10 h-3 w-3 -translate-x-1/2 rounded-full border-2 sm:left-4 {{ match ($status) { 'active' => 'animate-pulse border-green-300 bg-green-500 ring-4 ring-green-100', 'past' => 'border-gray-200 bg-gray-300', default => 'border-gray-300 bg-white' } }}"></div>

                        <div class="ml-4 sm:ml-6">
                            <div class="mb-1 flex items-center gap-2">
                                <span class="rounded px-2 py-0.5 font-mono text-xs {{ match ($status) { 'active' => 'bg-green-100 font-bold text-green-700', 'past' => 'bg-gray-100 text-gray-400', default => 'bg-gray-50 text-gray-500' } }}">
                                    {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                </span>
                                @if ($status === 'active')
                                    <span class="animate-pulse text-xs font-bold text-green-600">Идёт сейчас</span>
                                @elseif ($status === 'past')
                                    <span class="text-xs text-gray-400">Завершено</span>
                                @endif
                            </div>

                            <div class="rounded-xl border p-3 transition-all sm:p-4 {{ match ($status) { 'active' => 'border-green-300 bg-green-50 shadow-md', 'past' => 'border-gray-100 bg-gray-50 opacity-60', default => 'border-gray-100 bg-white' } }}">
                                <div class="flex items-start gap-3">
                                    @if ($scheduleEvent->icon_image)
                                        <img src="{{ Storage::url($scheduleEvent->icon_image) }}" alt="" class="h-6 w-6 flex-shrink-0 object-contain" style="max-width:24px;max-height:24px;">
                                    @elseif ($scheduleEvent->icon)
                                        <span class="text-2xl">{{ $scheduleEvent->icon }}</span>
                                    @else
                                        <span class="text-2xl">📌</span>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold sm:text-base">{{ $scheduleEvent->title }}</h4>
                                        @if ($scheduleEvent->description)
                                            <p class="mt-0.5 text-xs text-gray-500 sm:text-sm">{{ $scheduleEvent->description }}</p>
                                        @endif

                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @if ($scheduleEvent->speaker)
                                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">{{ $scheduleEvent->speaker->name }}</span>
                                            @endif
                                            @if ($scheduleEvent->location)
                                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">📍 {{ $scheduleEvent->location }}</span>
                                            @endif
                                        </div>

                                        <div class="relative mt-2" x-data="{ open: false }">
                                            <button @click="open = ! open" class="text-xs text-blue-600 hover:underline">Добавить в календарь</button>
                                            <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 z-20 mt-1 w-44 rounded border bg-white py-1 shadow-lg">
                                                <a href="{{ route('ical.single', $scheduleEvent) }}" class="block px-4 py-1 text-sm hover:bg-gray-100">Скачать .ics</a>
                                                <a href="{{ $googleUrl }}" target="_blank" class="block px-4 py-1 text-sm hover:bg-gray-100">Google Calendar</a>
                                                <a href="{{ route('ical.single', $scheduleEvent) }}" class="block px-4 py-1 text-sm hover:bg-gray-100">Apple Calendar</a>
                                                <a href="{{ route('ical.qr.single', $scheduleEvent) }}" target="_blank" class="block px-4 py-1 text-sm hover:bg-gray-100">QR-код</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">Нет событий для отображения.</p>
        @endif
    @else
        <p class="text-center text-gray-500">Мероприятие не найдено.</p>
    @endif
</section>
