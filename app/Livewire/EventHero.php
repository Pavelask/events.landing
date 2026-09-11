<?php

namespace App\Livewire;

use App\Models\Event;
use App\Traits\ResolvesEvent;
use Livewire\Component;

class EventHero extends Component
{
    use ResolvesEvent;

    public ?Event $event = null;
    public $slides;

    public function mount(Event|string|null $event = null): void
    {
        $this->event = $this->resolveEvent($event);

        if (!$this->event) {
            $this->slides = collect();
            return;
        }

        $content = resolveEventContent($this->event);
        $this->slides = $content['slides'];
    }

    public function render()
    {
        return view('livewire.event-hero');
    }
}
