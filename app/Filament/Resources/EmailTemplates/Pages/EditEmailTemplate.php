<?php

namespace App\Filament\Resources\EmailTemplates\Pages;

use App\Filament\Resources\EmailTemplates\EmailTemplateResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditEmailTemplate extends EditRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Предпросмотр письма')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn () => route('email-templates.preview', $this->record), shouldOpenInNewTab: true),
        ];
    }
}
