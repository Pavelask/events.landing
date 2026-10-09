<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventsController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $query = Event::query()->with(['heroSlides', 'days']);

        $status = $request->query('status');
        if ($status) {
            $query->where('status', $status);
        }

        if ($request->boolean('upcoming')) {
            $query->upcoming();
        }

        if ($request->boolean('active')) {
            $query->active();
        }

        if (! $status && ! $request->filled('upcoming') && ! $request->filled('active')) {
            $query->published();
        }

        $perPage = $request->query('per_page', 15);
        $events = $query->orderBy('start_date')->paginate($perPage);

        return EventResource::collection($events);
    }

    public function show(string $slug): EventResource|JsonResponse
    {
        $event = Event::with([
            'heroSlides',
            'days.events.speaker',
            'speakers',
            'guests',
            'testimonials',
            'faqs',
            'documents',
        ])->where('slug', $slug)->first();

        if (! $event) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return new EventResource($event);
    }
}
