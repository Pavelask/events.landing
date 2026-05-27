<?php

namespace App\Livewire;

use Livewire\Component;

class EventRegistration extends Component
{
    public ?\App\Models\Event $event = null;

    public function mount(\App\Models\Event $event = null): void
    {
        $this->event = $event;
    }

    public function render()
    {
        return view('livewire.event-registration');
    }
}
