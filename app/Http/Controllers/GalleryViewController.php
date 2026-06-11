<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryViewController extends Controller
{
    public function increment(Request $request): JsonResponse
    {
        $slug = $request->input('event_slug');
        
        if ($slug) {
            $event = Event::where('slug', $slug)->first();
            if ($event) {
                $event->incrementGalleryViewCount();
            }
        }
        
        return response()->json(['success' => true]);
    }
}
