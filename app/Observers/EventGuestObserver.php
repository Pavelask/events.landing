<?php

namespace App\Observers;

use App\Models\EventGuest;
use Illuminate\Support\Facades\Cache;

class EventGuestObserver
{
    public function created(EventGuest $guest): void
    {
        Cache::forget('event_content_' . $guest->event_id);
    }

    public function updated(EventGuest $guest): void
    {
        Cache::forget('event_content_' . $guest->event_id);
    }

    public function deleted(EventGuest $guest): void
    {
        Cache::forget('event_content_' . $guest->event_id);
    }

    public function restored(EventGuest $guest): void
    {
        Cache::forget('event_content_' . $guest->event_id);
    }
}