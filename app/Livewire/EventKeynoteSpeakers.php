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

        if (!$this->event) {
            $this->guests = collect();
            return;
        }

        $content = resolveEventContent($this->event);
        $this->guests = $content['guests'];
    }

    public function render()
    {
        return view('livewire.event-keynote-speakers');
    }
}
