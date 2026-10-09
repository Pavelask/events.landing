<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Event $event): JsonResponse
    {
        $gallery = is_array($event->gallery)
            ? collect($event->gallery)
                ->filter()
                ->map(fn ($image) => Storage::url($image))
                ->values()
                ->all()
            : [];

        return response()->json([
            'data' => [
                'slug' => $event->slug,
                'gallery' => $gallery,
                'media_image' => $event->media_image ? Storage::url($event->media_image) : null,
                'media_description' => $event->media_description,
                'is_media_visible' => $event->is_media_visible,
                'external' => [
                    'url' => $event->gallery_external_url,
                    'description' => $event->gallery_external_description
                        ? clean_html($event->gallery_external_description)
                        : null,
                    'is_visible' => (bool) $event->is_gallery_external_visible,
                    'has_external_gallery' => $event->has_external_gallery,
                    'qr_url' => $event->gallery_external_qr_url,
                ],
            ],
        ]);
    }
}
