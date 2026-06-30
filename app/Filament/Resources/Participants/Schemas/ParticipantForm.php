<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Основная информация')
                ->schema([
                    Select::make('event_id')
                        ->label('Мероприятие')
                        ->relationship('event', 'title')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpanFull(),
                    Grid::make(4)->schema([
                        TextInput::make('name')
                            ->label('Имя')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->nullable()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Телефон')
                            ->nullable()
                            ->maxLength(20),
                    ]),
                ]),

            Section::make('QR и статус')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'registered' => 'Зарегистрирован',
                                'verified' => 'Подтверждён',
                                'arrived' => 'Прибыл',
                                'cancelled' => 'Отменён',
                            ])
                            ->default('registered')
                            ->required(),
                        TextInput::make('checkin_token')
                            ->label('Checkin Token')
                            ->nullable()
                            ->readOnly()
                            ->helperText('Генерируется автоматически'),
                        DateTimePicker::make('checked_in_at')
                            ->label('Время чек-ина')
                            ->nullable()
                            ->readOnly(),
                    ]),
                ]),

            Section::make('Дополнительно')
                ->schema([
                    Select::make('source')
                        ->label('Источник')
                        ->options([
                            'yandex_form' => 'Яндекс Форма',
                            'manual' => 'Ручной ввод',
                            'import' => 'Импорт',
                        ])
                        ->default('manual')
                        ->required(),
                    KeyValue::make('answers')
                        ->label('Ответы из формы')
                        ->nullable(),
                    KeyValue::make('utm_tags')
                        ->label('UTM-метки')
                        ->nullable(),
                ]),
        ]);
    }
}
