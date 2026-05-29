<section id="schedule" class="mx-auto max-w-5xl px-4 py-16 text-[var(--color-text)] sm:px-6 lg:px-8">
    <div class="mb-8 border-b border-[var(--color-border)] pb-6">
        <p class="mb-2 text-center text-xs font-semibold uppercase tracking-wide text-[var(--color-primary)]">Расписание мероприятия</p>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">{{ $event?->title ?? 'Название мероприятия' }}</h2>
                @if ($event)
                    <p class="mt-2 text-sm text-[var(--color-text-secondary)] sm:text-base">{{ $event->title }}</p>
                    @if ($event->start_date && $event->end_date)
                        <p class="mt-1 text-xs text-[var(--color-text-secondary)] sm:text-sm">{{ $event->start_date->translatedFormat('j F') }} — {{ $event->end_date->translatedFormat('j F Y') }}</p>
                    @endif
                    @if ($event->venue_name)
                        <p class="mt-1 text-xs text-[var(--color-text-secondary)] sm:text-sm">📍 {{ $event->venue_name }}</p>
                    @endif
                @endif
            </div>

            @if ($event)
                <div class="flex shrink-0 items-center gap-2">
                    <div class="relative" x-data="{ calOpen: false }" @click.outside="calOpen = false" @keydown.escape="calOpen = false">
                        <button @click="calOpen = ! calOpen" class="inline-flex items-center gap-2 rounded-[var(--radius-btn)] border border-[var(--color-border)] bg-white px-3.5 py-2 text-xs font-medium text-[var(--color-text)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] sm:text-sm">
                            <svg class="h-4 w-4 shrink-0 rounded-[var(--radius-round)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
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
        <div class="mb-8 flex gap-8 overflow-x-auto border-b border-[var(--color-border)] pb-0" role="tablist">
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
                    class="schedule-tab cursor-pointer whitespace-nowrap text-sm font-medium {{ $isActive ? 'active' : '' }} {{ ($isPast && !$isActive) ? 'opacity-60' : '' }}"
                >
                    <span>{{ $day->label }}</span>
                    @if ($isPast && ! $isActive)
                        <span class="ml-1 text-xs">(завершён)</span>
                    @elseif ($isToday && ! $isActive)
                        <span class="ml-1 text-xs">сегодня</span>
                    @endif
                </button>
            @endforeach
        </div>

        @if ($selectedDay)
            @php
                $now = \Carbon\Carbon::now();
            @endphp

            <div class="relative pl-10 sm:pl-12" wire:key="day-{{ $selectedDay->id }}">
                <div class="absolute bottom-0 left-4 top-2 w-px bg-[var(--color-border)]"></div>

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

                    <div class="relative pb-6 last:pb-0">
                        <div @class([
                            'absolute left-4 z-10 h-4 w-4 -translate-x-1/2 rounded-[var(--radius-round)] border-2',
                            'animate-pulse border-[var(--color-primary)] bg-[var(--color-primary)] ring-4 ring-[var(--color-primary)]/20' => $status === 'active',
                            'border-[var(--color-border)] bg-[var(--color-muted)]' => $status === 'past',
                            'border-[var(--color-primary)] bg-white' => $status === 'upcoming',
                        ])></div>

                        <div class="ml-4 sm:ml-6">
                            <div class="mb-2 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span @class([
                                        'rounded-[var(--radius-btn)] px-2 py-0.5 font-mono text-xs tabular-nums',
                                        'bg-[var(--color-primary)]/10 font-semibold text-[var(--color-primary)]' => $status === 'active',
                                        'bg-[var(--color-background)] text-[var(--color-muted)]' => $status === 'past',
                                        'bg-[var(--color-background)] text-[var(--color-muted)]' => $status === 'upcoming',
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
                                        <svg class="h-4 w-4 rounded-[var(--radius-round)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
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
                                'rounded-[var(--radius-card)] border p-4 transition sm:p-5',
                                'border-[var(--color-primary)] bg-[var(--color-primary)]/5 shadow-[0_0_0_1px_var(--color-primary)]' => $status === 'active',
                                'border-[var(--color-border)] bg-[var(--color-background)] opacity-60' => $status === 'past',
                                'border-[var(--color-border)] bg-white hover:shadow-sm' => $status === 'upcoming',
                            ])>
                                <div class="flex items-start gap-3">
                                    @if ($scheduleEvent->icon_image)
                                        <img src="{{ Storage::url($scheduleEvent->icon_image) }}" alt="" class="h-7 w-7 shrink-0 rounded-[var(--radius-round)] object-contain">
                                    @elseif ($scheduleEvent->icon)
                                        <span class="text-2xl leading-none">{{ $scheduleEvent->icon }}</span>
                                    @else
                                        <span class="text-2xl leading-none">📌</span>
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-semibold leading-snug sm:text-base">{{ $scheduleEvent->title }}</h4>

                                        @if ($scheduleEvent->description)
                                            <p class="mt-1 text-xs leading-relaxed text-[var(--color-text-secondary)] sm:text-sm">{{ $scheduleEvent->description }}</p>
                                        @endif

                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @if ($scheduleEvent->speaker)
                                                <span class="rounded-[var(--radius-btn)] bg-[var(--color-background)] px-2.5 py-1 text-xs text-[var(--color-muted)]">👤 {{ $scheduleEvent->speaker->name }}</span>
                                            @endif
                                            @if ($scheduleEvent->location)
                                                <span class="rounded-[var(--radius-btn)] bg-[var(--color-background)] px-2.5 py-1 text-xs text-[var(--color-muted)]">📍 {{ $scheduleEvent->location }}</span>
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
</section>

