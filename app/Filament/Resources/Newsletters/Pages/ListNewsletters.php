<?php

namespace App\Filament\Resources\Newsletters\Pages;

use App\Filament\Resources\Newsletters\NewsletterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListNewsletters extends ListRecords
{
    protected static string $resource = NewsletterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Создать рассылку'),
        ];
    }

    protected function getHeaderTitle(): string
    {
        return 'Рассылки';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Все'),
            'running' => Tab::make('В работе')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['pending', 'running'])),
            'completed' => Tab::make('Завершённые')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed')),
            'drafts' => Tab::make('Черновики')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft')),
        ];
    }
}
