<?php

namespace App\Filament\Resources\AnonParticipants;

use App\Filament\Resources\AnonParticipants\Pages\ListAnonParticipants;
use App\Filament\Resources\AnonParticipants\Schemas\AnonParticipantForm;
use App\Filament\Resources\AnonParticipants\Tables\AnonParticipantsTable;
use App\Models\AnonParticipant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AnonParticipantResource extends Resource
{
    protected static ?string $model = AnonParticipant::class;

    protected static string|UnitEnum|null $navigationGroup = 'Мероприятия';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Участники (API)';

    public static function form(Schema $schema): Schema
    {
        return AnonParticipantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnonParticipantsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnonParticipants::route('/'),
            'edit' => \App\Filament\Resources\AnonParticipants\Pages\EditAnonParticipant::route('/{record}/edit'),
        ];
    }
}
