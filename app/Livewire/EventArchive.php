<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;

class EventArchive extends Component
{
    public int $limit = 1;

    public ?Event $lastCompletedEvent = null;

    public function mount(int $limit = 1): void
    {
        $this->limit = $limit;
        $this->lastCompletedEvent = Event::completed()
            ->with('heroSlides')
            ->orderByDesc('end_date')
            ->first();
    }

    public function render()
    {
        return view('livewire.event-archive');
    }
}
