<?php

namespace App\Services;

use App\Models\ScheduleEvent;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class IcalGenerator
{
    public function generateFromEvents(Collection $events): string
    {
        $lines = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//Event Schedule//RU', 'CALSCALE:GREGORIAN', 'METHOD:PUBLISH'];

        foreach ($events as $event) {
            $lines = array_merge($lines, $this->eventLines($event));
        }

        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", $lines) . "\r\n";
    }

    public function generateSingle(ScheduleEvent $event): string
    {
        return $this->generateFromEvents(collect([$event]));
    }

    private function eventLines(ScheduleEvent $event): array
    {
        $event->loadMissing(['day.event', 'speaker']);
        $date = $event->day->date->toDateString();
        $tz = $event->day->event->timezone ?? config('app.timezone');
        $start = Carbon::parse($date . ' ' . $event->start_time->format('H:i:s'), $tz);
        $end = $event->end_time
            ? Carbon::parse($date . ' ' . $event->end_time->format('H:i:s'), $tz)
            : $start->copy()->addHour();

        return [
            'BEGIN:VEVENT',
            'UID:' . $event->id . '-' . md5($event->title . $start->timestamp) . '@event-platform',
            'DTSTAMP:' . now('UTC')->format('Ymd\THis\Z'),
            'DTSTART:' . $start->utc()->format('Ymd\THis\Z'),
            'DTEND:' . $end->utc()->format('Ymd\THis\Z'),
            'SUMMARY:' . $this->escape($event->title),
            'DESCRIPTION:' . $this->escape(trim(($event->description ?? '') . ($event->speaker ? "\nСпикер: {$event->speaker->name}" : ''))),
            'LOCATION:' . $this->escape($event->location ?? $event->day->event?->venue_name ?? ''),
            'BEGIN:VALARM',
            'TRIGGER:-PT15M',
            'ACTION:DISPLAY',
            'DESCRIPTION:' . $this->escape($event->title),
            'END:VALARM',
            'END:VEVENT',
        ];
    }

    private function escape(string $value): string
    {
        return str_replace(["\\", ';', ',', "\n", "\r"], ["\\\\", '\\;', '\\,', '\\n', ''], $value);
    }
}
