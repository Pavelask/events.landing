<?php

use App\Http\Controllers\Api\YandexWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/yandex/register', [YandexWebhookController::class, 'handle']);
