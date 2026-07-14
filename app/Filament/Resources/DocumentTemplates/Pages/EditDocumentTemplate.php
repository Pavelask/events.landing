<?php

namespace App\Filament\Resources\DocumentTemplates\Pages;

use App\Filament\Resources\DocumentTemplates\DocumentTemplateResource;
use App\Services\PdfGeneratorService;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditDocumentTemplate extends EditRecord
{
    protected static string $resource = DocumentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Предпросмотр PDF')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->action(function () {
                    $template = $this->record;
                    $service = app(PdfGeneratorService::class);
                    $tempFile = $service->getPreview($template);

                    response()->download($tempFile, 'preview_' . $template->slug . '.pdf', [
                        'Content-Type' => 'application/pdf',
                    ])->deleteFileAfterSend(true)->send();
                })
                ->requiresConfirmation(false),
        ];
    }
}
