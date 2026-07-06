<?php

namespace App\Filament\Resources\AnonParticipants\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnonParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Основная информация')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('event_id')
                                ->label('Мероприятие')
                                ->relationship('event', 'title')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled(),
                            Select::make('status')
                                ->label('Статус')
                                ->options([
                                    'registered' => 'Зарегистрирован',
                                    'arrived' => 'Прибыл',
                                    'cancelled' => 'Отменён',
                                ])
                                ->default('registered')
                                ->required(),
                            TextInput::make('answer_id')
                                ->label('Answer ID')
                                ->disabled()
                                ->dehydrated(false),
                        ]),
                    ]),

                Section::make('Чек-ин и билеты')
                    ->schema([
                        Grid::make(3)->schema([
                            DateTimePicker::make('checked_in_at')
                                ->label('Время чек-ина')
                                ->nullable()
                                ->disabled(),
                            DateTimePicker::make('ticket_sent_at')
                                ->label('Билет отправлен')
                                ->nullable()
                                ->disabled(),
                            TextInput::make('checkin_token')
                                ->label('Checkin Token')
                                ->disabled()
                                ->dehydrated(false),
                        ]),
                    ]),

                Section::make('Отметки о выдаче')
                    ->schema([
                        Grid::make(3)->schema([
                            Toggle::make('souvenir_given')
                                ->label('Сувенир')
                                ->default(false),
                            Toggle::make('documentation_given')
                                ->label('Документация')
                                ->default(false),
                            Toggle::make('clothing_given')
                                ->label('Одежда')
                                ->default(false),
                        ]),
                    ]),
            ]);
    }
}
