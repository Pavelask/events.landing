<?php

namespace App\Livewire;

use Livewire\Component;

class EventGallery extends Component
{
    public ?\App\Models\Event $event = null;

    public function mount(?\App\Models\Event $event = null): void
    {
        $this->event = $event ?? \App\Models\Event::upcoming()->first();
    }

    public function render()
    {
        return view('livewire.event-gallery');
    }
}