<?php

namespace App\Observers;

use App\Models\EventTestimonial;
use Illuminate\Support\Facades\Cache;

class EventTestimonialObserver
{
    public function created(EventTestimonial $testimonial): void
    {
        Cache::forget('event_testimonials_' . $testimonial->event_id);
    }

    public function updated(EventTestimonial $testimonial): void
    {
        Cache::forget('event_testimonials_' . $testimonial->event_id);
    }

    public function deleted(EventTestimonial $testimonial): void
    {
        Cache::forget('event_testimonials_' . $testimonial->event_id);
    }

    public function restored(EventTestimonial $testimonial): void
    {
        Cache::forget('event_testimonials_' . $testimonial->event_id);
    }
}