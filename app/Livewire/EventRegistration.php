<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;

class EventRegistration extends Component
{
    public ?Event $event = null;

    public function mount(?Event $event = null): void
    {
        $this->event = $event;
    }

    public function render()
    {
        return view('livewire.event-registration');
    }
}
