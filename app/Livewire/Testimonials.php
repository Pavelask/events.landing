<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Testimonial;
use Livewire\Attributes\Name;
use Livewire\Component;

#[Name('testimonials')]
class Testimonials extends Component
{
    public ?Event $event = null;

    public function mount(Event|string|null $event = null, ?string $eventSlug = null): void
    {
        $this->event = $this->resolveEvent($event, $eventSlug);
    }

    public function render()
    {
        if (!$this->event) {
            return view('livewire.testimonials', ['testimonials' => collect()]);
        }

        $testimonials = $this->event->eventTestimonials()
            ->where('is_visible', true)
            ->with(['testimonial' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get()
            ->pluck('testimonial')
            ->filter();

        return view('livewire.testimonials', [
            'testimonials' => $testimonials,
        ]);
    }

    private function resolveEvent(Event|string|null $event, ?string $eventSlug): ?Event
    {
        if ($event instanceof Event) {
            return $event;
        }

        $slug = $eventSlug ?? (is_string($event) ? $event : null);

        if ($slug) {
            return Event::where('slug', $slug)->first();
        }

        return Event::active()->first() ?? Event::upcoming()->orderBy('start_date')->first();
    }
}
