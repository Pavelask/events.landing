<?php

use App\Http\Controllers\CheckinController;
use App\Http\Controllers\FaviconController;
use App\Http\Controllers\GalleryViewController;
use App\Http\Controllers\IcalController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketPdfController;
use App\Livewire\EventRegistration;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

Route::get('/favicon/{event:slug}.png', [FaviconController::class, 'show'])->name('event.favicon');
Route::get('/apple-touch-icon/{event:slug}.png', [FaviconController::class, 'appleTouch'])->name('event.apple-touch-icon');

Route::get('/', function () {
    $activeEvent = resolveActiveEvent();

    if (!$activeEvent) {
        return view('no-events');
    }

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
    $lastCompletedEvent = \App\Models\Event::archived()->with('heroSlides')->orderByDesc('end_date')->first();
    return view('archive', compact('lastCompletedEvent'));
})->name('archive');

Route::get('/events/{event:slug}/register', EventRegistration::class)->name('event.register');

Route::get('/events/{event:slug}/register-anon', \App\Livewire\AnonRegistration::class)->name('event.register.anon');

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

// API для инкремента счётчика просмотров галереи
Route::post('/api/gallery-view', [GalleryViewController::class, 'increment'])->name('gallery.view.increment');

// Страницы политик
Route::get('/privacy-policy', function () {
    $activeEvent = resolveActiveEvent();

    if (!$activeEvent || !$activeEvent->privacy_policy || !$activeEvent->show_privacy_section) {
        abort(404);
    }

    return view('privacy-policy', compact('activeEvent'));
})->name('privacy.policy');

Route::get('/personal-data-consent', function () {
    $activeEvent = resolveActiveEvent();

    if (!$activeEvent || !$activeEvent->personal_data_consent || !$activeEvent->show_personal_data_consent) {
        abort(404);
    }

    return view('personal-data-consent', compact('activeEvent'));
})->name('personal.data.consent');

Route::get('/cookie-policy', function () {
    $activeEvent = resolveActiveEvent();

    if (!$activeEvent || !$activeEvent->privacy_cookie_policy || !$activeEvent->show_cookie_banner) {
        abort(404);
    }

    return view('cookie-policy', compact('activeEvent'));
})->name('cookie.policy');

// Ticket, Checkin, Recovery routes
Route::get('/ticket/{token}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/ticket/{token}/pdf', [TicketPdfController::class, 'download'])->name('ticket.pdf');
Route::get('/checkin/{token}', [CheckinController::class, 'handle'])->name('checkin.handle')->middleware('auth');
Route::get('/recovery', [RecoveryController::class, 'showForm'])->name('recovery.form');
Route::post('/recovery', [RecoveryController::class, 'sendCode'])->name('recovery.send');
Route::get('/recovery/code', [RecoveryController::class, 'showCodeForm'])->name('recovery.code.form');
Route::post('/recovery/code', [RecoveryController::class, 'verifyCode'])->name('recovery.code.verify');

// Export download
Route::get('/exports/{filename}', function (string $filename) {
    $path = storage_path("app/private/exports/{$filename}");

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->download($path)->deleteFileAfterSend(true);
})->name('export.download')->middleware('auth');

// Consent PDF download
Route::get('/consents/{participant}/download', function (\App\Models\Participant $participant) {
    abort_unless(auth()->check(), 403);

    if (!$participant->consent_pdf_path) {
        abort(404);
    }

    $path = storage_path('app/private/' . $participant->consent_pdf_path);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->download($path, "consent_{$participant->id}.pdf", [
        'Content-Type' => 'application/pdf',
    ]);
})->name('consent.download')->middleware('auth');

// Document template preview
Route::get('/document-templates/{documentTemplate}/preview', function (\App\Models\DocumentTemplate $documentTemplate) {
    abort_unless(auth()->check(), 403);

    $service = app(\App\Services\PdfGeneratorService::class);
    $tempFile = $service->getPreview($documentTemplate);

    return response()->file($tempFile, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="preview_' . $documentTemplate->slug . '.pdf"',
    ])->deleteFileAfterSend(true);
})->name('document-templates.preview')->middleware('auth');

// Redirect login to Filament admin
Route::get('/login', fn () => redirect('/admin/login'))->name('login');

// Yandex Form test page
Route::get('/yandex-test', function () {
    return view('yandex-test');
})->name('yandex-test');
