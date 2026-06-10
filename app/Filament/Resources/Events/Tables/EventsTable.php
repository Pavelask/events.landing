<?php

namespace App\Filament\Resources\Events\Tables;

use App\Models\Event;
use App\Services\IcalGenerator;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster_image')->label('Постер')->disk('public')->circular(),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (Event $record): string => $record->start_date->format('d M Y') . ' - ' . $record->end_date->format('d M Y')),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Черновик',
                        'published' => 'Опубликовано',
                        default => $state,
                    }),
                TextColumn::make('schedule_events_count')->label('Событий')->counts('scheduleEvents')->sortable(),
                TextColumn::make('speakers_count')->label('Спикеров')->counts('speakers')->sortable(),
                TextColumn::make('guests_count')->label('Гостей')->counts('guests')->sortable(),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Статус')->options([
                    'draft' => 'Черновик',
                    'published' => 'Опубликовано',
                ]),
            ])
            ->recordActions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export_ical')
                        ->label('Экспорт в iCal')
                        ->action(function (Collection $records): void {
                            $events = $records
                                ->load('days.events.speaker')
                                ->flatMap(fn (Event $record) => $record->days->flatMap->events);

                            Storage::disk('local')->put('exports/events.ics', app(IcalGenerator::class)->generateFromEvents($events));
                        }),
                ]),
            ]);
    }
}
