<?php

namespace App\Filament\Resources\DocumentTemplates\Pages;

use App\Filament\Resources\DocumentTemplates\DocumentTemplateResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\Width;

class EditDocument extends Page
{
    use InteractsWithRecord;

    protected static string $resource = DocumentTemplateResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    public function getView(): string
    {
        return 'filament.pages.edit-document';
    }

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Назад')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => static::getResource()::getUrl('edit', ['record' => $this->record])),
        ];
    }

    protected function getHeaderTitle(): string
    {
        return 'Редактор документа: ' . $this->record->name;
    }
}