<?php

namespace App\Filament\Resources\Exports;

use App\Filament\Resources\Exports\Pages\ListExports;
use App\Models\Export;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExportResource extends Resource
{
    protected static ?string $model = Export::class;

    protected static ?string $slug = 'exports';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('exporter')
                    ->label('Тип экспорта')
                    ->formatStateUsing(fn (string $state): string => match (true) {
                        str_contains($state, 'AnonParticipant') => 'Участники (API)',
                        str_contains($state, 'Participant') => 'Участники',
                        default => class_basename($state),
                    })
                    ->badge()
                    ->color('info'),
                TextColumn::make('total_rows')
                    ->label('Всего')
                    ->sortable(),
                TextColumn::make('successful_rows')
                    ->label('Успешно')
                    ->sortable()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),
                TextColumn::make('file_name')
                    ->label('Файл')
                    ->limit(30)
                    ->copyable()
                    ->placeholder('—'),
                IconColumn::make('status')
                    ->label('Статус')
                    ->boolean()
                    ->getStateUsing(fn (Export $record): bool => (bool) $record->completed_at),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Завершён')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('exporter')
                    ->label('Тип')
                    ->options([
                        'App\\Exports\\AnonParticipantExport' => 'Участники (API)',
                        'App\\Exports\\ParticipantExport' => 'Участники',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->iconSize('md')
                    ->color('primary')
                    ->tooltip('Скачать файл')
                    ->url(fn (Export $record): ?string => $record->file_name
                        ? route('export.download', $record->file_name)
                        : null
                    )
                    ->openUrlInNewTab()
                    ->visible(fn (Export $record): bool => (bool) $record->file_name),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExports::route('/'),
        ];
    }
}
