<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GuestResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GuestsController extends Controller
{
    public function index(Event $event): AnonymousResourceCollection
    {
        $event->load('guests');

        return GuestResource::collection($event->guests);
    }
}
