<?php

use App\Http\Controllers\IcalController;
use App\Livewire\EventRegistration;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $activeEvent = Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->active()->first()
        ?? Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->upcoming()->orderBy('start_date')->first()
        ?? Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->recentlyCompleted()->orderByDesc('end_date')->first();
    return view('home', compact('activeEvent'));
})->name('home');

Route::get('/registration', function () {
    $today = now()->startOfDay();
    $event = Event::published()
        ->where('status', 'published')
        ->where(function ($q) use ($today) {
            $q->where('start_date', '>=', $today)
                ->orWhere(function ($q2) use ($today) {
                    $q2->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today);
                });
        })
        ->where('is_registration_open', true)
        ->orderBy('start_date')
        ->first();

    // Если событие не найдено или это недавно завершённое событие - редирект на главную
    if (!$event || $event->is_recently_completed) {
        return redirect()->route('home')->with('message', 'Регистрация на это мероприятие закрыта.');
    }

    return view('registration', compact('event'));
})->name('registration');

Route::get('/events/{event:slug}', fn(Event $event) => view('event.show', compact('event')))->name('event.show');

Route::get('/archive', function () {
    $lastCompletedEvent = \App\Models\Event::completed()->with('heroSlides')->orderByDesc('end_date')->first();
    return view('archive', compact('lastCompletedEvent'));
})->name('archive');

Route::get('/events/{event:slug}/register', EventRegistration::class)->name('event.register');

Route::prefix('ical')->name('ical.')->group(function (): void {
    Route::get('/event/{scheduleEvent}', [IcalController::class, 'singleEvent'])->name('single');
    Route::get('/day/{day}', [IcalController::class, 'fullDay'])->name('day');
    Route::get('/full/{event:slug}', [IcalController::class, 'fullEvent'])->name('full');
    Route::get('/qr/event/{scheduleEvent}', [IcalController::class, 'qrSingle'])->name('qr.single');
    Route::get('/qr/day/{day}', [IcalController::class, 'qrDay'])->name('qr.day');
});

// Маршрут для офлайн-страницы
Route::get('/offline', function () {
    return view('errors.offline');
})->name('offline');

// Health check для проверки подключения
Route::get('/health', function () {
    return response('', 200)->header('Content-Type', 'text/plain');
});

// Страницы политик
Route::get('/privacy-policy', function () {
    $activeEvent = Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->active()->first()
        ?? Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->upcoming()->orderBy('start_date')->first()
        ?? Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->recentlyCompleted()->orderByDesc('end_date')->first();
    return view('privacy-policy', compact('activeEvent'));
})->name('privacy.policy');

Route::get('/personal-data-consent', function () {
    $activeEvent = Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->active()->first()
        ?? Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->upcoming()->orderBy('start_date')->first()
        ?? Event::published()->with(['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker'])->recentlyCompleted()->orderByDesc('end_date')->first();
    return view('personal-data-consent', compact('activeEvent'));
})->name('personal.data.consent');
