<?php

namespace App\Filament\Resources\FormTemplates\Actions;

use App\Services\YandexFormsApi;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ImportYandexFieldsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Загрузить поля из Яндекс.Форм')
            ->icon('heroicon-o-arrow-down-circle')
            ->color('primary')
            ->requiresConfirmation()
            ->modalHeading('Загрузить поля из Яндекс.Форм?')
            ->modalDescription('Текущие вопросы будут заменены на полную структуру формы из Яндекс.Форм.')
            ->action(fn () => $this->importFields());
    }

    protected function importFields(): void
    {
        $livewire = $this->getLivewire();
        $formId = $livewire->data['yandex_form_id']
            ?? $livewire->record?->yandex_form_id
            ?? null;

        if (empty($formId)) {
            Notification::make()
                ->warning()
                ->title('Укажите ID формы Яндекс')
                ->body('Сначала заполните поле «ID формы Яндекс».')
                ->send();

            return;
        }

        $api = app(YandexFormsApi::class);
        $items = $api->getQuestions($formId);

        if (empty($items)) {
            Notification::make()
                ->danger()
                ->title('Не удалось загрузить вопросы')
                ->body('Проверьте токен (YANDEX_OAUTH_TOKEN) и ID формы.')
                ->send();

            return;
        }

        $questions = $api->mapQuestions($items);

        $livewire->form->fill(['questions' => $questions]);

        Notification::make()
            ->success()
            ->title('Поля формы загружены')
            ->body('Импортировано вопросов: ' . count($questions) . '. Нажмите «Сохранить», чтобы применить.')
            ->send();
    }
}