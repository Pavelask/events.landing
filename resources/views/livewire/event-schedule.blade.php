<section id="schedule" class="bg-white py-20 text-[var(--color-text)]">
    <div class="mx-auto max-w-4xl px-6">
        <div class="mb-12 border-b border-[var(--color-border)] pb-8">
            <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Расписание мероприятия</p>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="mt-3 text-4xl font-bold text-[var(--color-text)]">Расписание</h2>
                    @if ($event)
                        @if ($event->start_date && $event->end_date)
                            <p class="mt-2 text-sm text-[var(--color-text-secondary)] sm:text-base">{{ $event->start_date->translatedFormat('j F') }} — {{ $event->end_date->translatedFormat('j F Y') }}</p>
                        @endif
                        @if ($event->venue_name)
                            <p class="mt-1 text-sm text-[var(--color-text-secondary)] flex items-center gap-1.5">
                                <x-heroicon-o-map-pin class="h-4 w-4 text-[var(--color-muted)]" />
                                {{ $event->venue_name }}
                            </p>
                        @endif
                    @endif
                </div>

                @if ($event)
                    <div class="flex shrink-0 items-center gap-2">
                        <div class="relative" x-data="{ calOpen: false }" @click.outside="calOpen = false" @keydown.escape="calOpen = false">
                            <button @click="calOpen = ! calOpen" class="inline-flex items-center gap-2 rounded-[var(--radius-btn)] border border-[var(--color-border)] bg-white px-3.5 py-2 text-xs font-medium text-[var(--color-text)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] sm:text-sm">
                                <x-heroicon-o-calendar class="h-4 w-4 shrink-0" />
                                Добавить в календарь
                            </button>
                            <div x-show="calOpen" x-cloak class="absolute right-0 z-20 mt-1 w-48 rounded-[var(--radius-card)] border border-[var(--color-border)] bg-white py-1 shadow-lg">
                                @if ($selectedDay)
                                    <a href="{{ route('ical.day', $selectedDay) }}" class="block px-4 py-1.5 text-sm text-[var(--color-text)] hover:bg-[var(--color-background)]">Текущий день</a>
                                @endif
                                <a href="{{ route('ical.full', $event) }}" class="block px-4 py-1.5 text-sm text-[var(--color-text)] hover:bg-[var(--color-background)]">Все дни</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @if ($event)
        {{-- Underline-tab навигация --}}
        <div class="mb-8 flex gap-2" role="tablist">
            @foreach ($days as $day)
                @php
                    $todayDate = \Carbon\Carbon::today()->toDateString();
                    $dayDate = $day->date->toDateString();
                    $isActive = $day->id === $selectedDayId;
                    $isPast = $dayDate < $todayDate;
                    $isToday = $dayDate === $todayDate;
                @endphp
                <button
                    type="button"
                    wire:click="selectDay({{ $day->id }})"
                    role="tab"
                    aria-selected="{{ $isActive ? 'true' : 'false' }}"
                    class="flex-1 cursor-pointer whitespace-nowrap text-sm font-medium transition rounded-[var(--radius-btn)] px-4 py-2
                        {{ $isActive ? 'bg-[var(--color-primary)] text-white shadow-md hover:bg-[var(--color-primary)]' : 'bg-[var(--color-background)] text-[var(--color-text)] hover:bg-[var(--color-background)]' }}
                        {{ ($isPast && !$isActive) ? 'opacity-60' : '' }}"
                >
                    <span class="inline-flex flex-col items-center">
                        <span>{{ $day->label }}</span>
                        @if ($isPast)
                            <span class="mt-0.5 text-xs {{ $isActive ? 'text-white' : 'text-[var(--color-muted)]' }}">завершён</span>
                        @elseif ($isToday)
                            <span class="mt-0.5 text-xs {{ $isActive ? 'text-white' : 'text-[var(--color-muted)]' }}">сегодня</span>
                        @endif
                    </span>
                </button>
            @endforeach
        </div>

        @if ($selectedDay)
            @php
                // Отладка
                // dump('Event timezone:', $selectedDay->event->timezone ?? config('app.timezone'));
            @endphp

            <div class="relative" wire:key="day-{{ $selectedDay->id }}">
                {{-- Вертикальная линия таймлинии --}}
                <div class="absolute left-4 top-0 bottom-0 w-px bg-[var(--color-border)]"></div>

                @foreach ($selectedDay->events as $scheduleEvent)
                    @php
                        $tz = $selectedDay->event->timezone ?? config('app.timezone');
                        $start = \Carbon\Carbon::parse($selectedDay->date->format('Y-m-d') . ' ' . $scheduleEvent->start_time->format('H:i:s'), $tz);
                        $end = $scheduleEvent->end_time
                            ? \Carbon\Carbon::parse($selectedDay->date->format('Y-m-d') . ' ' . $scheduleEvent->end_time->format('H:i:s'), $tz)
                            : $start->copy()->addHour();

                        $now = \Carbon\Carbon::now($tz);

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

                    <div class="relative pb-6 last:pb-0">
                        {{-- Шар-индикатор на таймлинии --}}
                        <div @class([
                            'absolute left-4 top-2 z-10 h-3 w-3 -translate-x-1/2 rounded-[var(--radius-round)] border-2',
                            'border-[var(--color-primary)] bg-[var(--color-primary)] ring-4 ring-[var(--color-primary)]/20' => $status === 'active',
                            'border-[var(--color-border)] bg-[var(--color-muted)]' => $status === 'past',
                            'border-[var(--color-primary)] bg-white' => $status === 'upcoming',
                        ])></div>

                        <div class="ml-8 sm:ml-10 mt-1.5">
                            <div class="mb-2 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span @class([
                                        'rounded-[var(--radius-btn)] px-3 py-1 font-mono text-sm tabular-nums font-semibold',
                                        'bg-[var(--color-primary)]/10 text-[var(--color-primary)]' => $status === 'active',
                                        'bg-[var(--color-background)] text-[var(--color-text)]' => $status === 'past',
                                        'bg-[var(--color-background)] text-[var(--color-text)]' => $status === 'upcoming',
                                    ])>
                                        {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                    </span>

                                    @if ($status === 'active')
                                        <span class="inline-flex items-center gap-1 rounded-[var(--radius-btn)] bg-[var(--color-primary)]/10 px-2 py-0.5 text-[11px] font-semibold text-[var(--color-primary)]">● Идёт сейчас</span>
                                    @elseif ($status === 'past')
                                        <span class="text-xs text-[var(--color-muted)]">Завершено</span>
                                    @endif
                                </div>

                                <div class="relative" x-data="{ evtCalOpen: false }" @click.outside="evtCalOpen = false" @keydown.escape="evtCalOpen = false">
                                    <button @click="evtCalOpen = ! evtCalOpen" class="rounded-[var(--radius-btn)] p-1 text-[var(--color-muted)] transition hover:bg-[var(--color-background)] hover:text-[var(--color-primary)]" title="Добавить в календарь">
                                        <x-heroicon-o-calendar class="h-4 w-4" />
                                    </button>
                                    <div x-show="evtCalOpen" x-cloak @click.away="evtCalOpen = false" class="absolute right-0 z-20 mt-1 w-44 rounded-[var(--radius-card)] border border-[var(--color-border)] bg-white py-1 shadow-lg">
                                        <a href="{{ route('ical.single', $scheduleEvent) }}" class="block px-4 py-1.5 text-sm text-[var(--color-text)] hover:bg-[var(--color-background)]">Скачать .ics</a>
                                        <a href="{{ $googleUrl }}" target="_blank" class="block px-4 py-1.5 text-sm text-[var(--color-text)] hover:bg-[var(--color-background)]">Google Calendar</a>
                                        <a href="{{ route('ical.single', $scheduleEvent) }}" class="block px-4 py-1.5 text-sm text-[var(--color-text)] hover:bg-[var(--color-background)]">Apple Calendar</a>
                                        <a href="{{ route('ical.qr.single', $scheduleEvent) }}" target="_blank" class="block px-4 py-1.5 text-sm text-[var(--color-text)] hover:bg-[var(--color-background)]">QR-код</a>
                                    </div>
                                </div>
                            </div>

                            <div @class([
                                'schedule-card rounded-[var(--radius-card)] border p-4 transition sm:p-5',
                                'border-[var(--color-primary)] bg-[var(--color-primary)]/5 shadow-[0_0_0_1px_var(--color-primary)]' => $status === 'active',
                                'border-[var(--color-border)] bg-[var(--color-background)] opacity-60' => $status === 'past',
                                'border-[var(--color-border)] bg-white hover:shadow-sm' => $status === 'upcoming',
                            ])>
                                <div class="flex items-start gap-4">
                                    @if ($scheduleEvent->speaker && $scheduleEvent->speaker->photo)
                                        <img
                                            src="{{ Storage::url($scheduleEvent->speaker->photo) }}"
                                            alt="{{ $scheduleEvent->speaker->name }}"
                                            class="h-16 w-16 shrink-0 rounded-full object-cover border-2 border-[var(--color-primary)]"
                                        >
                                    @elseif ($scheduleEvent->icon_image)
                                        <img src="{{ Storage::url($scheduleEvent->icon_image) }}" alt="" class="h-16 w-16 shrink-0 rounded-full object-cover border-2 border-[var(--color-primary)]">
                                    @elseif ($scheduleEvent->icon)
                                        <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-[var(--color-background)] text-3xl leading-none">{{ $scheduleEvent->icon }}</span>
                                    @else
                                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)]/10">
                                            <x-heroicon-o-clock class="h-7 w-7 text-[var(--color-primary)]" />
                                        </div>
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-semibold leading-snug sm:text-base">{{ $scheduleEvent->title }}</h4>

                                        @if ($scheduleEvent->description)
                                            <p class="mt-1 text-xs leading-relaxed text-[var(--color-text-secondary)] sm:text-sm">{{ $scheduleEvent->description }}</p>
                                        @endif

                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @if ($scheduleEvent->speaker)
                                                <span class="inline-flex items-center gap-1 rounded-[var(--radius-btn)] bg-[var(--color-background)] px-2.5 py-1 text-xs text-[var(--color-muted)]">
                                                    <x-heroicon-o-user class="h-3 w-3" />
                                                    {{ $scheduleEvent->speaker->name }}
                                                </span>
                                            @endif
                                            @if ($scheduleEvent->location)
                                                <span class="inline-flex items-center gap-1 rounded-[var(--radius-btn)] border border-[var(--color-primary)]/60 bg-[var(--color-primary)]/5 px-2.5 py-1 text-xs text-[var(--color-primary)]">
                                                    <x-heroicon-o-map-pin class="h-3 w-3" />
                                                    {{ $scheduleEvent->location }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-background)] px-6 py-12 text-center text-[var(--color-muted)]">
                <p class="text-sm sm:text-base">Нет событий для отображения.</p>
            </div>
        @endif
    @else
        <div class="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-background)] px-6 py-12 text-center text-[var(--color-muted)]">
            <p class="text-sm sm:text-base">Мероприятие не найдено.</p>
        </div>
    @endif
    </div>
</section>

