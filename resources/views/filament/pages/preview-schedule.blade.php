<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex flex-wrap gap-2">
            @foreach ($days as $day)
                <button
                    wire:click="selectDay({{ $day->id }})"
                    @class([
                        'rounded-xl px-4 py-2 text-sm font-medium',
                        'bg-primary-600 text-white' => $selectedDayId === $day->id,
                        'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200' => $selectedDayId !== $day->id,
                    ])
                >
                    {{ $day->date->format('d.m') }} · {{ $day->label }}
                </button>
            @endforeach
        </div>

        @if ($this->selectedDay)
            <div class="space-y-4">
                @foreach ($this->selectedDay->events as $scheduleEvent)
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="text-sm text-gray-500">
                            {{ $scheduleEvent->start_time?->format('H:i') }}
                            @if ($scheduleEvent->end_time)
                                - {{ $scheduleEvent->end_time->format('H:i') }}
                            @endif
                        </div>
                        <div class="mt-1 flex items-center gap-2 text-lg font-semibold">
                            @if ($scheduleEvent->icon_image)
                                <img src="{{ Storage::url($scheduleEvent->icon_image) }}" alt="" class="h-6 w-6 object-contain" style="max-width:24px;max-height:24px;">
                            @elseif ($scheduleEvent->icon)
                                <span>{{ $scheduleEvent->icon }}</span>
                            @endif
                            {{ $scheduleEvent->title }}
                        </div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            @if ($scheduleEvent->speaker)
                                Спикер: {{ $scheduleEvent->speaker->name }}
                            @endif
                            @if ($scheduleEvent->location)
                                · Место: {{ $scheduleEvent->location }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-xl bg-gray-100 p-6 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                Расписание пока не заполнено.
            </div>
        @endif
    </div>
</x-filament-panels::page>
