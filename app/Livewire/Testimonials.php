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
        // при монтировании компонента и кэшируется (resolveTestimonials).
        $this->testimonials = $this->event ? resolveTestimonials($this->event) : collect();
    }

    public function render()
    {
        return view('livewire.testimonials', [
            'testimonials' => $this->testimonials,
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
