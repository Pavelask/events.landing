<?php

namespace App\Providers;

use App\Models\EventDay;
use App\Models\ScheduleEvent;
use App\Observers\EventDayObserver;
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
    }
}
