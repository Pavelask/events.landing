<?php

namespace App\Filament\Resources\AnonParticipants\Pages;

use App\Filament\Resources\AnonParticipants\AnonParticipantResource;
use App\Services\YandexFormsApi;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;

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
                ->label($question['label'])
                ->placeholder('Загружается из Яндекс Формы');
        }

        return [
            Section::make('Основная информация')
                ->schema([
                    \Filament\Forms\Components\Select::make('event_id')
                        ->label('Мероприятие')
                        ->relationship('event', 'title')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disabled()
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        \Filament\Forms\Components\Select::make('status')
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
                    Grid::make(1)->schema($customFields),
                ]),

            Section::make('Чек-ин и билеты')
                ->schema([
                    Grid::make(3)->schema([
                        \Filament\Forms\Components\DateTimePicker::make('checked_in_at')
                            ->label('Время чек-ина')
                            ->nullable()
                            ->disabled(),
                        \Filament\Forms\Components\DateTimePicker::make('ticket_sent_at')
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
                        \Filament\Forms\Components\Toggle::make('souvenir_given')
                            ->label('Сувенир')
                            ->default(false),
                        \Filament\Forms\Components\Toggle::make('documentation_given')
                            ->label('Документация')
                            ->default(false),
                        \Filament\Forms\Components\Toggle::make('clothing_given')
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
        $yandexApi = app(YandexFormsApi::class);

        $formId = $record->event->formTemplate->yandex_form_id ?? null;
        $yandexData = null;

        if ($formId && $record->answer_id && !str_starts_with($record->answer_id, 'LOCAL_')) {
            $yandexData = $yandexApi->getAnswer($formId, $record->answer_id);
        }

        $localData = $record->local_data ?? [];
        $questions = $record->event->formTemplate->questions ?? [];

        $personalData = [
            'yandex_name' => $yandexData['answerer']['fields']['name'] ?? ($localData['yandex_name'] ?? ''),
            'yandex_email' => $yandexData['answerer']['email'] ?? ($localData['yandex_email'] ?? ''),
            'yandex_phone' => $yandexData['answerer']['fields']['phone'] ?? ($localData['yandex_phone'] ?? ''),
        ];

        foreach ($questions as $index => $question) {
            $slot = 'custom_' . ($index + 1);
            $personalData['custom_' . $question['slug']] = $yandexData['answerer']['fields'][$slot] ?? ($localData['custom_' . $question['slug']] ?? '');
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
