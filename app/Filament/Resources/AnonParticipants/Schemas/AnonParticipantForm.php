<?php

namespace App\Filament\Resources\AnonParticipants\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AnonParticipantForm
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
                            ->disabled()
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
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

                Section::make('Данные из Яндекс Формы')
                    ->description('Временная выгрузка персональных данных. Изменения сохраняются локально.')
                    ->icon('heroicon-o-cloud-arrow-down')
                    ->schema([
                        Grid::make(1)->schema([
                            TextInput::make('yandex_name')
                                ->label('ФИО')
                                ->placeholder('Загружается из Яндекс Формы'),
                            TextInput::make('yandex_email')
                                ->label('Email')
                                ->email()
                                ->placeholder('Загружается из Яндекс Формы'),
                            TextInput::make('yandex_phone')
                                ->label('Телефон')
                                ->placeholder('Загружается из Яндекс Формы'),
                        ]),
                        KeyValue::make('custom_fields')
                            ->label('Дополнительные поля')
                            ->placeholder('Нет данных')
                            ->dehydrated(false)
                            ->visible(false),
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
