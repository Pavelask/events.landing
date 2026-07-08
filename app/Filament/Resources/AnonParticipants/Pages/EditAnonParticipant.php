<?php

namespace App\Filament\Resources\AnonParticipants\Pages;

use App\Filament\Resources\AnonParticipants\AnonParticipantResource;
use App\Services\YandexFormsApi;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
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

            Section::make('Данные из Яндекс Формы')
                ->description('Загружаются из ответа в Яндекс Формах при открытии страницы.')
                ->icon('heroicon-o-cloud-arrow-down')
                ->schema([
                    Grid::make(1)->schema([
                        TextInput::make('yandex_name')
                            ->label('ФИО')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('yandex_email')
                            ->label('Email')
                            ->email()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('yandex_phone')
                            ->label('Телефон')
                            ->disabled()
                            ->dehydrated(false),
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

        if (str_starts_with($record->answer_id, 'LOCAL_')) {
            Notification::make()
                ->title('Данные недоступны')
                ->body('Ответ был создан локально (без отправки в Яндекс Форму). Персональные данные отсутствуют.')
                ->warning()
                ->send();
            return;
        }

        $yandexApi = app(YandexFormsApi::class);
        $formId = $record->event->formTemplate->yandex_form_id ?? null;

        if (!$formId) {
            Notification::make()
                ->title('Ошибка')
                ->body('Для этого мероприятия не задан form_id шаблона формы.')
                ->danger()
                ->send();
            return;
        }

        $answer = $yandexApi->getAnswer($formId, $record->answer_id);

        if (!$answer) {
            Notification::make()
                ->title('Ошибка загрузки данных из Яндекс Формы')
                ->body("Не удалось получить ответ #{\$record->answer_id} из формы {\$formId}. Проверьте токен, права доступа и принадлежность к организации.")
                ->danger()
                ->send();
            return;
        }

        $answerData = $answer['data'] ?? [];
        $answerMap = [];
        foreach ($answerData as $item) {
            $label = $item['label'] ?? $item['id'] ?? '';
            $answerMap[mb_mb_strtolower($label)] = $item['value'] ?? '';
        }

        $personalData = [
            'yandex_name' => $answerMap['фио участника'] ?? $answerMap['имя'] ?? $answerMap['name'] ?? $answerMap['фио'] ?? '',
            'yandex_email' => $answerMap['почта'] ?? $answerMap['email'] ?? $answerMap['электронная почта'] ?? '',
            'yandex_phone' => $answerMap['телефон'] ?? $answerMap['phone'] ?? '',
        ];

        $questions = $record->event->formTemplate->questions ?? [];
        foreach ($questions as $question) {
            $label = mb_strtolower($question['label'] ?? '');
            $personalData['custom_' . $question['slug']] = $answerMap[$label] ?? '';
        }

        $this->form->fill([
            ...$this->data,
            ...$personalData,
        ]);
    }
}
