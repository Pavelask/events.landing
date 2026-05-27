<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Support\Collection;
use Livewire\Component;

class EventSpeakers extends Component
{
    public ?Event $event = null;

    public Collection $speakers;

    public function mount(Event|string|null $event = null, ?string $eventSlug = null): void
    {
        $this->event = $this->resolveEvent($event, $eventSlug);
        $this->speakers = $this->event?->speakers()->get() ?? collect();
    }

    public function render()
    {
        return view('livewire.event-speakers');
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
