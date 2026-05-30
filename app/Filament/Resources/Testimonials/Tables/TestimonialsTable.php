<?php

namespace App\Filament\Resources\Testimonials\Tables;

use App\Models\Testimonial;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
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
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
