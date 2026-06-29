<?php

namespace App\Filament\Resources\EventDays\Tables;

use App\Models\EventDay;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Carbon\Carbon;

class EventDaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.title')
                    ->label('Мероприятие')
                    ->searchable()
                    ->limit(30)
                    ->hidden(fn () => filled($table->getGrouping())),
                TextColumn::make('date')
                    ->label('Дата')
                    ->date('d.m.Y')
                    ->sortable()
                    ->color(function (EventDay $record) {
                        $today = Carbon::today();
                        $date = Carbon::parse($record->date);
                        if ($date->isSameDay($today)) return 'success';
                        if ($date->isPast()) return 'gray';
                        return 'primary';
                    }),
                TextColumn::make('label')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('events_count')
                    ->label('Событий')
                    ->counts('events')
                    ->badge()
                    ->color('primary'),
                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->defaultGroup('event.title')
            ->groups([
                Group::make('event.title')
                    ->label('Мероприятие'),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Мероприятие')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('event_status')
                    ->label('Статус мероприятия')
                    ->options([
                        'draft' => 'Черновик',
                        'published' => 'Опубликовано',
                        'completed' => 'Завершено',
                        'archived' => 'В архиве',
                    ])
                    ->query(fn ($query, $state) => filled($state['value'])
                        ? $query->whereHas('event', fn ($q) => $q->where('status', $state['value']))
                        : $query
                    ),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->iconButton()
                    ->size(\Filament\Support\Enums\Size::Medium),
                \Filament\Actions\Action::make('clone')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->iconButton()
                    ->size(\Filament\Support\Enums\Size::Medium)
                    ->action(function (EventDay $record) {
                        $newDay = $record->replicate();
                        $newDay->label = $record->label . ' (копия)';
                        $newDay->sort_order = $record->sort_order + 1;
                        unset($newDay->events_count);

                        $date = $record->date->copy()->addDay();
                        while (EventDay::where('event_id', $record->event_id)->where('date', $date)->exists()) {
                            $date->addDay();
                        }
                        $newDay->date = $date;
                        $newDay->saveQuietly();

                        foreach ($record->events as $event) {
                            $newEvent = $event->replicate();
                            $newEvent->event_day_id = $newDay->id;
                            $newEvent->saveQuietly();
                        }

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('День скопирован')
                            ->send();
                    }),
                \Filament\Actions\DeleteAction::make()
                    ->iconButton()
                    ->size(\Filament\Support\Enums\Size::Medium),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\BulkAction::make('activate')
                        ->label('Активировать')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    \Filament\Actions\BulkAction::make('deactivate')
                        ->label('Деактивировать')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
