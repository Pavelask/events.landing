<?php

namespace App\Filament\Resources\Testimonials\Tables;

use App\Models\Testimonial;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;


class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Фото')
                    ->circular()
                    ->width(50)
                    ->height(50),

                TextColumn::make('author_name')
                    ->label('Автор')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('content')
                    ->label('Отзыв')
                    ->limit(100)
                    ->html()
                    ->wrap()
                    ->lineClamp(3),

                TextColumn::make('is_active')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Активен' : 'Скрыт')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                ToggleColumn::make('is_active')
                    ->label('Включен')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('gray')
                    ->afterStateUpdated(function (Testimonial $record, $state) {
                        $record->update(['is_active' => $state]);
                    }),

                TextColumn::make('sort_order')
                    ->label('Сортировка')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Статус')
                    ->options([
                        true => 'Активен',
                        false => 'Скрыт',
                    ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Активировать выбранные')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->modalHeading('Активировать отзывы')
                        ->modalDescription('Вы действительно хотите активировать выбранные отзывы?')
                        ->modalWidth(Width::Large)
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(fn ($record) => $record->update(['is_active' => true]));
                        }),

                    BulkAction::make('deactivate')
                        ->label('Скрыть выбранные')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->modalHeading('Скрыть отзывы')
                        ->modalDescription('Вы действительно хотите скрыть выбранные отзывы?')
                        ->modalWidth(Width::Large)
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(fn ($record) => $record->update(['is_active' => false]));
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
