<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Livewire\Attributes\Name;
use Livewire\Component;

#[Name('testimonials')]
class Testimonials extends Component
{
    public ?Event $event = null;

    public Collection $testimonials;

    public function mount(Event|string|null $event = null, ?string $eventSlug = null): void
    {
        $this->event = $this->resolveEvent($event, $eventSlug);
        // Пункт 9 оптимизаций: запрос отзывов выполняется один раз
        // при монтировании компонента, а не на каждый render().
        $this->testimonials = $this->loadTestimonials();
    }

    public function render()
    {
        return view('livewire.testimonials', [
            'testimonials' => $this->testimonials,
        ]);
    }

    private function loadTestimonials(): Collection
    {
        if (!$this->event) {
            return collect();
        }

        return $this->event->eventTestimonials()
            ->where('is_visible', true)
            ->with(['testimonial' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get()
            ->pluck('testimonial')
            ->filter();
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
