<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventDay;
use App\Models\ScheduleEvent;
use App\Services\IcalGenerator;
use Carbon\Carbon;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IcalController extends Controller
{
    public function singleEvent(ScheduleEvent $scheduleEvent, IcalGenerator $generator): Response
    {
        return response(
            $generator->generateSingle($scheduleEvent->load(['day.event', 'speaker'])),
            200,
            $this->headers('event-' . $scheduleEvent->id . '.ics')
        );
    }

    public function fullDay(EventDay $day, IcalGenerator $generator): Response
    {
        return response(
            $generator->generateFromEvents($day->events()->with(['day.event', 'speaker'])->get()),
            200,
            $this->headers('day-' . $day->id . '.ics')
        );
    }

    public function fullEvent(Event $event, IcalGenerator $generator): Response
    {
        $events = $event->days()->with(['events.speaker'])->get()->flatMap->events;

        return response($generator->generateFromEvents($events), 200, $this->headers($event->slug . '.ics'));
    }

    public function qrSingle(ScheduleEvent $scheduleEvent): Response
    {
        $scheduleEvent->load(['day.event']);
        $date = $scheduleEvent->day->date->toDateString();
        $start = Carbon::parse($date . ' ' . $scheduleEvent->start_time->format('H:i:s'));
        $end = $scheduleEvent->end_time
            ? Carbon::parse($date . ' ' . $scheduleEvent->end_time->format('H:i:s'))
            : $start->copy()->addHour();

        $url = 'https://calendar.google.com/calendar/render?' . http_build_query([
            'action' => 'TEMPLATE',
            'text' => $scheduleEvent->title,
            'dates' => $start->utc()->format('Ymd\THis\Z') . '/' . $end->utc()->format('Ymd\THis\Z'),
            'details' => $scheduleEvent->description,
            'location' => $scheduleEvent->location ?? $scheduleEvent->day->event?->venue_name,
        ]);

        return response(QrCode::format('png')->size(300)->margin(2)->errorCorrection('M')->generate($url), 200, ['Content-Type' => 'image/png']);
    }

    public function qrDay(EventDay $day): Response
    {
        return response(QrCode::format('png')->size(300)->margin(2)->errorCorrection('M')->generate(route('ical.day', $day)), 200, ['Content-Type' => 'image/png']);
    }

    private function headers(string $filename): array
    {
        return [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
    }
}
