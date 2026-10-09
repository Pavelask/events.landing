<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SpeakerResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpeakersController extends Controller
{
    public function index(Event $event): AnonymousResourceCollection
    {
        $event->load('speakers');

        return SpeakerResource::collection($event->speakers);
    }
}
