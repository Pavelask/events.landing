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
        $speakersCount = $event->speakers()->count();
        $guestsCount = $event->guests()->count();

        $scheduleStats = ScheduleEvent::query()
            ->join('event_days as d', 'd.id', '=', 'schedule_events.event_day_id')
            ->where('d.event_id', $event->id)
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN d.date < ? THEN 1 ELSE 0 END) as completed', [Carbon::today()])
            ->first();

        $eventsCount = (int) ($scheduleStats->total ?? 0);
        $completedEvents = (int) ($scheduleStats->completed ?? 0);

        $progressPercent = $eventsCount > 0 ? round(($completedEvents / $eventsCount) * 100) : 0;

        $today = Carbon::today();
        $isActive = $event->start_date?->lte($today) && $event->end_date?->gte($today);
        $isPast = $event->end_date?->lt($today);

        $participantStats = $event->participants()
            ->selectRaw(
                'COUNT(*) as total, '
                . "SUM(CASE WHEN status = 'arrived' THEN 1 ELSE 0 END) as arrived, "
                . "SUM(CASE WHEN status = 'registered' THEN 1 ELSE 0 END) as registered"
            )
            ->first();

        $totalParticipants = (int) ($participantStats->total ?? 0);
        $arrivedParticipants = (int) ($participantStats->arrived ?? 0);
        $registeredParticipants = (int) ($participantStats->registered ?? 0);

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
