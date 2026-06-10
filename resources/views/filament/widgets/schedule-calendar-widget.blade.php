<x-filament-widgets::widget>
    <x-filament::section heading="Ближайшие события расписания">
        <div class="space-y-3">
            @forelse ($this->getScheduleEvents() as $item)
                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-3 dark:bg-gray-900">
                    <div>
                        <div class="font-medium">{{ $item->title }}</div>
                        <div class="text-sm text-gray-500">
                            @if($item->day?->date)
                                {{ $item->day->date->format('d.m.Y') }} · {{ $item->start_time?->format('H:i') ?? '—' }}
                            @else
                                {{ $item->start_time?->format('H:i') ?? '—' }}
                            @endif
                            @if($item->location) · {{ $item->location }} @endif
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">{{ $item->speaker?->name }}</div>
                </div>
            @empty
                <div class="text-sm text-gray-500">Событий пока нет.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
