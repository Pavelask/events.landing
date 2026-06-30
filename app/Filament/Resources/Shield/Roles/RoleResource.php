<?php

namespace App\Filament\Resources\Shield\Roles;

use App\Filament\Resources\Shield\Roles\Pages\EditRole;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as BaseRoleResource;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole;

class RoleResource extends BaseRoleResource
{
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
