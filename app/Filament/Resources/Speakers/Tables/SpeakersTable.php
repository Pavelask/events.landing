<?php

namespace App\Filament\Resources\Speakers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpeakersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Фото')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(asset('storage/img/Simpleicons_Interface_user-black-close-up-shape.svg.png')),
                TextColumn::make('name')->label('Имя')->searchable()->sortable(),
                TextColumn::make('position')->label('Должность')->searchable(),
                TextColumn::make('organization')->label('Организация')->searchable(),
            ])
            ->recordActions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
