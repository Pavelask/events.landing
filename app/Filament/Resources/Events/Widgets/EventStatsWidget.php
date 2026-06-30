<?php

namespace App\Filament\Resources\Events\Widgets;

use App\Models\Event;
use App\Models\ScheduleEvent;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class EventStatsWidget extends StatsOverviewWidget
{
    public ?Event $record = null;

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $event = $this->record;

        if (!$event) {
            return [];
        }

        $daysCount = $event->days()->count();
        $eventsCount = ScheduleEvent::whereHas('day', fn ($q) => $q->where('event_id', $event->id))->count();
        $speakersCount = $event->speakers()->count();
        $guestsCount = $event->guests()->count();

        $completedEvents = ScheduleEvent::whereHas('day', function ($q) use ($event) {
            $q->where('event_id', $event->id)->where('date', '<', Carbon::today());
        })->count();

        $progressPercent = $eventsCount > 0 ? round(($completedEvents / $eventsCount) * 100) : 0;

        $today = Carbon::today();
        $isActive = $event->start_date?->lte($today) && $event->end_date?->gte($today);
        $isPast = $event->end_date?->lt($today);

        $totalParticipants = $event->participants()->count();
        $arrivedParticipants = $event->participants()->where('status', 'arrived')->count();
        $registeredParticipants = $event->participants()->where('status', 'registered')->count();

        return [
            Stat::make('Дни', $daysCount)
                ->description('В расписании')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),
            Stat::make('События', $eventsCount)
                ->description('В программе')
                ->descriptionIcon('heroicon-o-clock')
                ->color('info'),
            Stat::make('Спикеры', $speakersCount)
                ->description('Подключены')
                ->descriptionIcon('heroicon-o-user')
                ->color($speakersCount > 0 ? 'success' : 'gray'),
            Stat::make('Гости', $guestsCount)
                ->description('Приглашены')
                ->descriptionIcon('heroicon-o-user-group')
                ->color($guestsCount > 0 ? 'success' : 'gray'),
            Stat::make('Регистрации', $totalParticipants)
                ->description('Всего участников')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Прибыло', $arrivedParticipants)
                ->description('Отмечены на входе')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),
            Stat::make('Ожидается', $registeredParticipants)
                ->description('Ещё не пришли')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Прогресс', $progressPercent . '%')
                ->description($isActive ? 'Идёт сейчас' : ($isPast ? 'Завершено' : 'Предстоит'))
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($isActive ? 'success' : ($isPast ? 'gray' : 'warning')),
        ];
    }
}
