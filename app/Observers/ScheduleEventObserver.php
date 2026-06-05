<?php

namespace App\Observers;

use App\Livewire\EventSchedule;
use App\Models\ScheduleEvent;

class ScheduleEventObserver
{
    /**
     * Handle the ScheduleEvent "created" event.
     */
    public function created(ScheduleEvent $scheduleEvent): void
    {
        $this->invalidateCache($scheduleEvent);
    }

    /**
     * Handle the ScheduleEvent "updated" event.
     */
    public function updated(ScheduleEvent $scheduleEvent): void
    {
        $this->invalidateCache($scheduleEvent);
    }

    /**
     * Handle the ScheduleEvent "deleted" event.
     */
    public function deleted(ScheduleEvent $scheduleEvent): void
    {
        $this->invalidateCache($scheduleEvent);
    }

    /**
     * Handle the ScheduleEvent "restored" event.
     */
    public function restored(ScheduleEvent $scheduleEvent): void
    {
        $this->invalidateCache($scheduleEvent);
    }

    /**
     * Invalidate cache for the related event
     */
    protected function invalidateCache(ScheduleEvent $scheduleEvent): void
    {
        $eventDay = $scheduleEvent->day;
        
        if ($eventDay && $eventDay->event) {
            EventSchedule::invalidateCache($eventDay->event->id);
        }
    }
}
