<?php

namespace App\Filament\Resources\EventDays\Pages;

use App\Filament\Resources\EventDays\EventDayResource;
use Filament\Resources\Pages\EditRecord;

class EditEventDay extends EditRecord
{
    protected static string $resource = EventDayResource::class;

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
