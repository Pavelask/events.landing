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
        $this->slides = $this->event?->heroSlides()->where('is_active', true)->get() ?? collect();
    }

    public function render()
    {
        return view('livewire.event-hero');
    }
}
