<?php

namespace App\Filament\Resources\Testimonials\Pages;

use App\Filament\Resources\Testimonials\TestimonialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTestimonial extends EditRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
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
