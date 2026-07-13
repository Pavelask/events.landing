<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Response;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FaviconController extends Controller
{
    public function show(Event $event): Response
    {
        return $this->serve($event, 32);
    }

    public function appleTouch(Event $event): Response
    {
        return $this->serve($event, 180);
    }

    private function serve(Event $event, int $size): Response
    {
        $cachedPath = storage_path("app/public/events/favicons/{$event->id}-{$size}.png");

        if (file_exists($cachedPath)) {
            return response()->file($cachedPath, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        if ($event->poster_image) {
            $sourcePath = storage_path('app/public/' . $event->poster_image);

            if (file_exists($sourcePath)) {
                try {
                    $manager = ImageManager::usingDriver(Driver::class);
                    $image = $manager->decodePath($sourcePath)->resize($size, $size);
                    $encoded = $image->encodeUsingMediaType('image/png');

                    $dir = dirname($cachedPath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    file_put_contents($cachedPath, $encoded->toString());

                    return response()->file($cachedPath, [
                        'Content-Type' => 'image/png',
                        'Cache-Control' => 'public, max-age=86400',
                    ]);
                } catch (\Throwable $e) {
                    // fall through to fallback
                }
            }
        }

        $fallback = public_path('favicon.png');
        return response()->file($fallback, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
