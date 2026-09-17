<?php

namespace App\Filament\Resources\Newsletters\Tables;

use App\Models\Newsletter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewslettersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('event.title')
                    ->label('Мероприятие')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('template.name')
                    ->label('Шаблон')
                    ->limit(30),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (Newsletter $record) => $record->status_color)
                    ->formatStateUsing(fn (Newsletter $record) => $record->status_label),

                TextColumn::make('total_count')
                    ->label('Всего')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sent_count')
                    ->label('Отправлено')
                    ->numeric()
                    ->color('success'),

                TextColumn::make('failed_count')
                    ->label('Ошибки')
                    ->numeric()
                    ->color('danger'),

                TextColumn::make('creator.name')
                    ->label('Создал'),

                TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('finished_at')
                    ->label('Завершена')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make()->label('')->icon('heroicon-o-pencil')->iconSize('md'),
                DeleteAction::make()->label('')->icon('heroicon-o-trash')->iconSize('md'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
