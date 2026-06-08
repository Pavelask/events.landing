<?php

namespace App\Livewire;

use Livewire\Component;

class EventHero extends Component
{
    public ?\App\Models\Event $event = null;
    public $slides;

    public function mount(?\App\Models\Event $event = null): void
    {
        $this->event = $event ?? \App\Models\Event::published()->with('heroSlides')->active()->first()
            ?? \App\Models\Event::published()->with('heroSlides')->upcoming()->orderBy('start_date')->first()
            ?? \App\Models\Event::published()->with('heroSlides')->recentlyCompleted()->orderByDesc('end_date')->first();
        $this->slides = $this->event?->heroSlides()->where('is_active', true)->get() ?? collect();
    }

    public function render()
    {
        return view('livewire.event-hero');
    }
}
