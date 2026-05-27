<?php

namespace App\Livewire;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class EventSchedule extends Component
{
    public ?Event $event = null;

    public int $selectedDayId = 0;

    public Collection $days;

    public ?\App\Models\EventDay $selectedDay = null;

    public function mount(Event|string|null $event = null, ?string $eventSlug = null): void
    {
        $this->event = $this->resolveEvent($event, $eventSlug);
        $this->days = $this->event?->days()->with(['events.speaker'])->get() ?? collect();

        $today = Carbon::today()->toDateString();
        $todayDay = $this->days->first(fn ($day): bool => $day->date->toDateString() === $today);
        $this->selectedDayId = $todayDay?->id ?? $this->days->first()?->id ?? 0;
        $this->loadSelectedDay();
    }

    public function selectDay(int $dayId): void
    {
        $this->selectedDayId = $dayId;
        $this->loadSelectedDay();
    }

    public function loadSelectedDay(): void
    {
        if ($this->selectedDayId <= 0 || !$this->event) {
            $this->selectedDay = null;
            return;
        }

        $this->selectedDay = \App\Models\EventDay::with(['events.speaker'])
            ->where('event_id', $this->event->id)
            ->where('id', $this->selectedDayId)
            ->first();
    }

    public function render()
    {
        return view('livewire.event-schedule');
    }

    private function resolveEvent(Event|string|null $event, ?string $eventSlug): ?Event
    {
        if ($event instanceof Event) {
            return $event;
        }

        $slug = $eventSlug ?? (is_string($event) ? $event : null);

        if ($slug) {
            return Event::where('slug', $slug)->firstOrFail();
        }

        return Event::active()->first() ?? Event::upcoming()->orderBy('start_date')->first();
    }
}
