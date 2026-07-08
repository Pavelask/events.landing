<?php

namespace App\Filament\Resources\AnonParticipants\Pages;

use App\Filament\Resources\AnonParticipants\AnonParticipantResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class EditAnonParticipant extends EditRecord
{
    protected static string $resource = AnonParticipantResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormSchema(): array
    {
        $record = $this->record;
        $questions = $record->event->formTemplate->questions ?? [];

        $customFields = [];
        foreach ($questions as $question) {
            $customFields[] = TextInput::make('custom_' . $question['slug'])
                ->label($question['label']);
        }

        return [
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

            Section::make('Данные участника')
                ->description('Персональные данные, сохранённые при регистрации.')
                ->icon('heroicon-o-user')
                ->schema([
                    Grid::make(1)->schema([
                        TextInput::make('yandex_name')
                            ->label('ФИО'),
                        TextInput::make('yandex_email')
                            ->label('Email')
                            ->email(),
                        TextInput::make('yandex_phone')
                            ->label('Телефон'),
                    ]),
                    Grid::make(1)->schema($customFields),
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
        ];
    }

    protected function fillForm(): void
    {
        parent::fillForm();

        $record = $this->record;
        $localData = $record->local_data ?? [];

        $personalData = [];
        foreach ($localData as $key => $value) {
            $personalData[$key] = $value;
        }

        $this->form->fill([
            ...$this->data,
            ...$personalData,
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $personalKeys = array_filter(array_keys($data), fn ($k) => str_starts_with($k, 'yandex_') || str_starts_with($k, 'custom_'));

        $localData = [];
        foreach ($personalKeys as $key) {
            $localData[$key] = $data[$key];
            unset($data[$key]);
        }

        $data['local_data'] = $localData;

        return $data;
    }
}
