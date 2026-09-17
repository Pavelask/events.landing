<?php

namespace App\Filament\Resources\EmailTemplates\Pages;

use App\Filament\Resources\EmailTemplates\EmailTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEmailTemplates extends ListRecords
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderTitle(): string
    {
        return 'Email-шаблоны';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Все'),
            'active' => Tab::make('Активные')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            'inactive' => Tab::make('Неактивные')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
            'system' => Tab::make('Системные')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('key', [
                    'registration-confirmation',
                    'ticket',
                    'ticket-reminder',
                    'verification-code',
                ])),
        ];
    }
}
