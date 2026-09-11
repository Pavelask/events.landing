<?php

namespace App\Observers;

use App\Models\EventSpeaker;
use Illuminate\Support\Facades\Cache;

class EventSpeakerObserver
{
    public function created(EventSpeaker $speaker): void
    {
        Cache::forget('event_content_' . $speaker->event_id);
    }

    public function updated(EventSpeaker $speaker): void
    {
        Cache::forget('event_content_' . $speaker->event_id);
    }

    public function deleted(EventSpeaker $speaker): void
    {
        Cache::forget('event_content_' . $speaker->event_id);
    }

    public function restored(EventSpeaker $speaker): void
    {
        Cache::forget('event_content_' . $speaker->event_id);
    }
}