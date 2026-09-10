<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Resources\Events\Schemas\EventForm;
use App\Filament\Resources\Events\Tables\EventsTable;
use App\Livewire\EventSchedule;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|UnitEnum|null $navigationGroup = 'Мероприятия';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Мероприятия';

    protected static ?string $modelLabel = 'Мероприятие';

    protected static ?string $pluralModelLabel = 'Мероприятия';

    protected static ?int $navigationSort = 1;
    
    public static function form(Schema $schema): Schema 
    { 
        return EventForm::configure($schema); 
    }
    
    public static function table(Table $table): Table 
    { 
        return EventsTable::configure($table); 
    }
    
    public static function getPages(): array 
    { 
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ]; 
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with([
            'heroSlides',
            'eventSpeakers',
            'eventGuests',
            'eventTestimonials',
            'eventFaqs',
            'documents',
        ]);
    }

    /**
     * Автоматическая инвалидация кэша после создания/обновления мероприятия
     */
    public static function afterSave(Event $event): void
    {
        EventSchedule::invalidateCache($event->id);
    }

    public static function afterDelete(Event $event): void
    {
        EventSchedule::invalidateCache($event->id);
    }

    protected static function afterCreate(Event $event): void
    {
        self::afterSave($event);
    }

    protected static function afterUpdate(Event $event): void
    {
        self::afterSave($event);
    }
}
