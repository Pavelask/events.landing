<?php

namespace App\Filament\Resources\AnonParticipants\Pages;

use App\Filament\Resources\AnonParticipants\AnonParticipantResource;
use Filament\Resources\Pages\ListRecords;

class ListAnonParticipants extends ListRecords
{
    protected static string $resource = AnonParticipantResource::class;
}
