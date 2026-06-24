<?php

namespace App\Traits;

use App\Models\Event;

trait ResolvesEvent
{
    private function resolveEvent(Event|string|null $event, ?string $eventSlug = null): ?Event
    {
        if ($event instanceof Event) {
            return $event;
        }

        $slug = $eventSlug ?? (is_string($event) ? $event : null);

        if ($slug) {
            return Event::where('slug', $slug)->firstOrFail();
        }

        return Event::active()->first()
            ?? Event::upcoming()->orderBy('start_date')->first()
            ?? Event::recentlyCompleted()->orderByDesc('end_date')->first();
    }
}
