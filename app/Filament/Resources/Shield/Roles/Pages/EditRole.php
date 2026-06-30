<?php

namespace App\Filament\Resources\Shield\Roles\Pages;

use BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole as BaseEditRole;
use Filament\Actions\Action;

class EditRole extends BaseEditRole
{
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Action::make('saveAndClose')
                ->label('Сохранить и закрыть')
                ->color('primary')
                ->action(function () {
                    $this->save(shouldRedirect: false, shouldSendSavedNotification: false);
                    $this->getSavedNotification()?->send();
                    $this->redirect(static::getResource()::getUrl('index'));
                }),
            $this->getCancelFormAction(),
        ];
    }
}
