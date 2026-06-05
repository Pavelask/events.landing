<?php

namespace App\Observers;

use App\Livewire\EventSchedule;
use App\Models\EventDay;

class EventDayObserver
{
    /**
     * Handle the EventDay "created" event.
     */
    public function created(EventDay $eventDay): void
    {
        $this->invalidateCache($eventDay);
    }

    /**
     * Handle the EventDay "updated" event.
     */
    public function updated(EventDay $eventDay): void
    {
        $this->invalidateCache($eventDay);
    }

    /**
     * Handle the EventDay "deleted" event.
     */
    public function deleted(EventDay $eventDay): void
    {
        $this->invalidateCache($eventDay);
    }

    /**
     * Handle the EventDay "restored" event.
     */
    public function restored(EventDay $eventDay): void
    {
        $this->invalidateCache($eventDay);
    }

    /**
     * Invalidate cache for the related event
     */
    protected function invalidateCache(EventDay $eventDay): void
    {
        if ($eventDay->event) {
            EventSchedule::invalidateCache($eventDay->event->id);
        }
    }
}
