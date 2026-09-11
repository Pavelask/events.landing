<?php

namespace App\Observers;

use App\Models\HeroSlide;
use Illuminate\Support\Facades\Cache;

class HeroSlideObserver
{
    public function created(HeroSlide $slide): void
    {
        Cache::forget('event_content_' . $slide->event_id);
    }

    public function updated(HeroSlide $slide): void
    {
        Cache::forget('event_content_' . $slide->event_id);
    }

    public function deleted(HeroSlide $slide): void
    {
        Cache::forget('event_content_' . $slide->event_id);
    }

    public function restored(HeroSlide $slide): void
    {
        Cache::forget('event_content_' . $slide->event_id);
    }
}