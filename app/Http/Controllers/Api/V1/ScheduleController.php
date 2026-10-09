<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\EventDayResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScheduleController extends Controller
{
    public function index(Event $event): AnonymousResourceCollection
    {
        $event->load('days.events.speaker');

        return EventDayResource::collection($event->days);
    }
}
