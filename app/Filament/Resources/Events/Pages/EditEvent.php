<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Events\Widgets\EventStatsWidget;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->color('danger'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EventStatsWidget::class,
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getSaveAndCloseFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveAndCloseFormAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('saveAndClose')
            ->label('Сохранить и закрыть')
            ->action(function () {
                $this->save(shouldRedirect: false, shouldSendSavedNotification: false);

                $this->getSavedNotification()?->send();

                $this->redirect(static::getResource()::getUrl('index'));
            });
    }
}
