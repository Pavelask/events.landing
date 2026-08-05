<?php

namespace App\Filament\Resources\Shield\Roles;

use App\Filament\Resources\Shield\Roles\Pages\EditRole;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as BaseRoleResource;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole;
use UnitEnum;

class RoleResource extends BaseRoleResource
{
    protected static string|UnitEnum|null $navigationGroup = 'Настройки';

    protected static ?string $navigationLabel = 'Роли';

    protected static ?string $modelLabel = 'Роль';

    protected static ?string $pluralModelLabel = 'Роли';

    protected static ?int $navigationSort = 11;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Настройки';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
