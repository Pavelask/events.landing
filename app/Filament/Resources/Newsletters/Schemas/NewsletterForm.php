<?php

namespace App\Filament\Resources\Newsletters\Schemas;

use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Newsletter;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NewsletterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Название рассылки')
                    ->required()
                    ->maxLength(255),

                Select::make('event_id')
                    ->label('Мероприятие')
                    ->options(fn () => Event::query()->orderBy('title')->pluck('title', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(),

                Select::make('email_template_id')
                    ->label('Шаблон письма')
                    ->options(fn () => EmailTemplate::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Каждому участнику письмо отправится с его персональными данными.'),

                Select::make('filters.statuses')
                    ->label('Статусы участников')
                    ->options([
                        'registered' => 'Зарегистрирован',
                        'verified' => 'Подтверждён',
                        'arrived' => 'Прибыл',
                        'cancelled' => 'Отменён',
                    ])
                    ->multiple()
                    ->default(['registered', 'verified'])
                    ->live()
                    ->helperText('Кому отправляем. По умолчанию — зарегистрированные и подтверждённые.'),

                Toggle::make('filters.only_without_ticket')
                    ->label('Только без отправленного билета')
                    ->default(false)
                    ->live(),

                Placeholder::make('recipients_count')
                    ->label('Получателей')
                    ->content(function (callable $get) {
                        $eventId = $get('event_id');
                        $statuses = $get('filters.statuses') ?: [];
                        $onlyWithoutTicket = $get('filters.only_without_ticket');

                        if (! $eventId) {
                            return 'Выберите мероприятие';
                        }

                        $temp = new Newsletter([
                            'event_id' => $eventId,
                            'filters' => [
                                'statuses' => $statuses,
                                'only_without_ticket' => $onlyWithoutTicket,
                            ],
                        ]);

                        return (string) $temp->recipients()->count();
                    })
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
