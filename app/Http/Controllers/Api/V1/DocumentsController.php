<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\EventDocumentResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DocumentsController extends Controller
{
    public function index(Event $event): AnonymousResourceCollection
    {
        $event->load('documents');

        return EventDocumentResource::collection($event->documents);
    }
}
