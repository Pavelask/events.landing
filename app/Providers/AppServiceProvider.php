<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\EventDay;
use App\Models\EventGuest;
use App\Models\EventSpeaker;
use App\Models\EventTestimonial;
use App\Models\HeroSlide;
use App\Models\ScheduleEvent;
use App\Observers\EventDayObserver;
use App\Observers\EventGuestObserver;
use App\Observers\EventObserver;
use App\Observers\EventSpeakerObserver;
use App\Observers\EventTestimonialObserver;
use App\Observers\HeroSlideObserver;
use App\Observers\ScheduleEventObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Регистрируем Observer для кэширования расписания
        ScheduleEvent::observe(ScheduleEventObserver::class);
        EventDay::observe(EventDayObserver::class);
        Event::observe(EventObserver::class);
        HeroSlide::observe(HeroSlideObserver::class);
        EventSpeaker::observe(EventSpeakerObserver::class);
        EventGuest::observe(EventGuestObserver::class);
        EventTestimonial::observe(EventTestimonialObserver::class);
    }
}
