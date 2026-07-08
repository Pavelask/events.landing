<?php

namespace App\Filament\Resources\AnonParticipants\Pages;

use App\Filament\Resources\AnonParticipants\AnonParticipantResource;
use Filament\Resources\Pages\EditRecord;

class EditAnonParticipant extends EditRecord
{
    protected static string $resource = AnonParticipantResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
