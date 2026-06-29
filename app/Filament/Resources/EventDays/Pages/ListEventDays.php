<?php

namespace App\Filament\Resources\EventDays\Pages;

use App\Filament\Resources\EventDays\EventDayResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListEventDays extends ListRecords
{
    protected static string $resource = EventDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Все дни'),
            'active' => Tab::make('Активные')
                ->modifyQueryUsing(fn ($query) => $query->where('is_active', true)),
        ];
    }
}
