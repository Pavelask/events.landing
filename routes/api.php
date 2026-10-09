<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DocumentsController;
use App\Http\Controllers\Api\V1\EventsController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\GalleryController;
use App\Http\Controllers\Api\V1\GuestsController;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\SpeakersController;
use App\Http\Controllers\Api\V1\TestimonialsController;
use App\Http\Controllers\Api\YandexWebhookController;
use App\Http\Controllers\GalleryViewController;
use Illuminate\Support\Facades\Route;

Route::post('/yandex/register', [YandexWebhookController::class, 'handle']);
Route::post('/gallery-view', [GalleryViewController::class, 'increment'])->name('gallery.view.increment');

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    Route::get('/events', [EventsController::class, 'index']);
    Route::get('/events/{event:slug}/schedule', [ScheduleController::class, 'index']);
    Route::get('/events/{event:slug}/speakers', [SpeakersController::class, 'index']);
    Route::get('/events/{event:slug}/guests', [GuestsController::class, 'index']);
    Route::get('/events/{event:slug}/faq', [FaqController::class, 'index']);
    Route::get('/events/{event:slug}/documents', [DocumentsController::class, 'index']);
    Route::get('/events/{event:slug}/gallery', [GalleryController::class, 'index']);
    Route::get('/events/{slug}', [EventsController::class, 'show']);
    Route::get('/testimonials', [TestimonialsController::class, 'index']);
});
