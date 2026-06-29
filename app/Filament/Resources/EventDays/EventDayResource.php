<?php

namespace App\Filament\Resources\EventDays;

use App\Filament\Resources\EventDays\Pages\CreateEventDay;
use App\Filament\Resources\EventDays\Pages\EditEventDay;
use App\Filament\Resources\EventDays\Pages\ListEventDays;
use App\Filament\Resources\EventDays\Schemas\EventDayForm;
use App\Filament\Resources\EventDays\Tables\EventDaysTable;
use App\Livewire\EventSchedule;
use App\Models\Event;
use App\Models\EventDay;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EventDayResource extends Resource
{
    protected static ?string $model = EventDay::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Расписание';
    protected static string|UnitEnum|null $navigationGroup = 'Мероприятия';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return EventDayForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventDaysTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEventDays::route('/'),
            'create' => CreateEventDay::route('/create'),
            'edit' => EditEventDay::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['events.speaker', 'event'])
            ->withCount('events');
    }

    public static function afterSave(EventDay $record): void
    {
        EventSchedule::invalidateCache($record->event_id);
    }

    public static function afterDelete(EventDay $record): void
    {
        EventSchedule::invalidateCache($record->event_id);
    }
}
