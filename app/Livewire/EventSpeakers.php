<?php

namespace App\Livewire;

use App\Models\Event;
use App\Traits\ResolvesEvent;
use Illuminate\Support\Collection;
use Livewire\Component;

class EventSpeakers extends Component
{
    use ResolvesEvent;

    public ?Event $event = null;

    public Collection $speakers;

    public function mount(Event|string|null $event = null, ?string $eventSlug = null): void
    {
        $this->event = $this->resolveEvent($event, $eventSlug);
        $this->speakers = $this->event?->eventSpeakers()
            ->where('is_visible', true)
            ->with('speaker')
            ->orderBy('sort_order')
            ->get() ?? collect();
    }

    public function render()
    {
        return view('livewire.event-speakers');
    }
}
