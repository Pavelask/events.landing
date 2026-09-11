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

        if (!$this->event) {
            $this->speakers = collect();
            return;
        }

        $content = resolveEventContent($this->event);
        $this->speakers = $content['speakers'];
    }

    public function render()
    {
        return view('livewire.event-speakers');
    }
}
