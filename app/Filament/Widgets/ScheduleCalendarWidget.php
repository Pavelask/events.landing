<?php

namespace App\Filament\Widgets;

use App\Models\ScheduleEvent;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class ScheduleCalendarWidget extends Widget
{
    protected string $view = 'filament.widgets.schedule-calendar-widget';

    protected int|string|array $columnSpan = 'full';

    public function getScheduleEvents(): Collection
    {
        return ScheduleEvent::query()
            ->with(['day.event', 'speaker'])
            ->join('event_days', 'schedule_events.event_day_id', '=', 'event_days.id')
            ->orderBy('event_days.date')
            ->orderBy('schedule_events.start_time')
            ->select('schedule_events.*')
            ->limit(10)
            ->get();
    }
}
