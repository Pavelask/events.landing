<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FaqController extends Controller
{
    public function index(Event $event): AnonymousResourceCollection
    {
        $event->load('faqs');

        return FaqResource::collection($event->faqs);
    }
}
