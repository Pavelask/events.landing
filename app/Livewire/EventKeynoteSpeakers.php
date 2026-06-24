<?php

namespace App\Livewire;

use App\Models\Event;
use App\Traits\ResolvesEvent;
use Illuminate\Support\Collection;
use Livewire\Component;

class EventKeynoteSpeakers extends Component
{
    use ResolvesEvent;

    public ?Event $event = null;

    public Collection $guests;

    public function mount(Event|string|null $event = null, ?string $eventSlug = null): void
    {
        $this->event = $this->resolveEvent($event, $eventSlug);
        $this->guests = $this->event?->eventGuests()
            ->where('is_visible', true)
            ->with('guest')
            ->orderBy('sort_order')
            ->get() ?? collect();
    }

    public function render()
    {
        return view('livewire.event-keynote-speakers');
    }
}
