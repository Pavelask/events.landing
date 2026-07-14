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
            Action::make('convertDocx')
                ->label('Конвертировать .docx')
                ->icon('heroicon-o-arrow-path')
                ->color('secondary')
                ->requiresConfirmation()
                ->modalHeading('Конвертировать .docx → HTML?')
                ->modalDescription('Текущий текст шаблона будет заменён на содержимое загруженного .docx файла')
                ->action(function () {
                    $file = $this->record->docx_file;
                    if (empty($file)) {
                        \Filament\Notifications\Notification::make()
                            ->warning()
                            ->title('Сначала загрузите .docx файл')
                            ->send();
                        return;
                    }

                    $disk = \Illuminate\Support\Facades\Storage::disk('public');
                    if (!$disk->exists($file)) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Файл не найден на диске')
                            ->send();
                        return;
                    }

                    $converter = app(\App\Services\DocxConverterService::class);
                    $fullPath = $disk->path($file);
                    $html = $converter->convertToHtml($fullPath);
                    $html = $converter->applyPlaceholders($html);

                    $this->record->update(['content' => $html ?: '<p></p>']);
                    $this->refreshFormData(['content']);

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Файл конвертирован')
                        ->send();
                }),

            Action::make('preview')
                ->label('Предпросмотр PDF')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn () => route('document-templates.preview', $this->record))
                ->openInNewTab()
                ->requiresConfirmation(false),
        ];
    }
}
