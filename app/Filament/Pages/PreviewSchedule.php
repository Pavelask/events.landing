<?php

namespace App\Filament\Pages;

use App\Models\EventDay;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
class PreviewSchedule extends Page
{
    protected string $view = 'filament.pages.preview-schedule';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationLabel = 'Предпросмотр расписания';

    public ?int $selectedDayId = null;

    public Collection $days;

    public function mount(): void
    {
        $this->days = EventDay::query()
            ->with(['event', 'events.speaker'])
            ->orderBy('date')
            ->get();

        if ($this->days->isNotEmpty() && ! $this->selectedDayId) {
            $this->selectedDayId = $this->days->first()->id;
        }
    }

    public function selectDay(int $dayId): void
    {
        $this->selectedDayId = $dayId;
    }

    public function getSelectedDayProperty(): ?EventDay
    {
        return $this->days->firstWhere('id', $this->selectedDayId);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('open_frontend')
                ->label('Открыть на сайте')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => $this->selectedDay?->event ? route('event.show', $this->selectedDay->event) : route('home'))
                ->openUrlInNewTab(),
            Action::make('refresh_preview')
                ->label('Обновить')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->mount()),
        ];
    }
}
