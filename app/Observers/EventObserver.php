<?php

namespace App\Observers;

use App\Models\Event;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class EventObserver
{
    public function saved(Event $event): void
    {
        if ($event->isDirty('poster_image')) {
            $this->generateFavicons($event);
        }
    }

    public function deleted(Event $event): void
    {
        $dir = storage_path('app/public/events/favicons');
        @unlink($dir . '/' . $event->id . '-32.png');
        @unlink($dir . '/' . $event->id . '-180.png');
    }

    private function generateFavicons(Event $event): void
    {
        if (!$event->poster_image) {
            return;
        }

        $sourcePath = storage_path('app/public/' . $event->poster_image);

        if (!file_exists($sourcePath)) {
            return;
        }

        $dir = storage_path('app/public/events/favicons');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $manager = ImageManager::usingDriver(Driver::class);

        $manager->decodePath($sourcePath)->resize(32, 32)
            ->encodeUsingMediaType('image/png')
            ->save($dir . '/' . $event->id . '-32.png');

        $manager->decodePath($sourcePath)->resize(180, 180)
            ->encodeUsingMediaType('image/png')
            ->save($dir . '/' . $event->id . '-180.png');
    }
}
