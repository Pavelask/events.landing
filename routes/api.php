<?php

use App\Http\Controllers\Api\V1\EventsController;
use App\Http\Controllers\Api\YandexWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/yandex/register', [YandexWebhookController::class, 'handle']);
Route::post('/gallery-view', [App\Http\Controllers\GalleryViewController::class, 'increment'])->name('gallery.view.increment');

Route::prefix('v1')->group(function () {
    Route::get('/events', [EventsController::class, 'index']);
    Route::get('/events/{slug}', [EventsController::class, 'show']);
});
