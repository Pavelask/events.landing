<?php

namespace App\Filament\Resources\FormTemplates\Pages;

use App\Filament\Resources\FormTemplates\Actions\ImportYandexFieldsAction;
use App\Filament\Resources\FormTemplates\FormTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormTemplate extends CreateRecord
{
    protected static string $resource = FormTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportYandexFieldsAction::make('importYandexFields'),
        ];
    }
}