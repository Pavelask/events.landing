<?php

namespace App\Filament\Resources\DocumentTemplates\Pages;

use App\Filament\Resources\DocumentTemplates\DocumentTemplateResource;
use App\Services\PdfGeneratorService;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditDocumentTemplate extends EditRecord
{
    protected static string $resource = DocumentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editOnlyOffice')
                ->label('Редактировать в OnlyOffice')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->url(fn () => static::getResource()::getUrl('edit-doc', ['record' => $this->record]))
                ->disabled(fn () => empty($this->record->docx_file))
                ->tooltip(fn () => empty($this->record->docx_file)
                    ? 'Сначала создайте .docx — загрузите файл или нажмите «Экспорт в .docx»'
                    : ''),

            Action::make('exportDocx')
                ->label('Экспорт в .docx')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(function () {
                    $html = $this->record->content ?: '<p></p>';

                    $phpWord = new \PhpOffice\PhpWord\PhpWord();
                    $section = $phpWord->addSection([
                        'breakType' => 'nextPage',
                        'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
                        'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(29.7),
                    ]);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $this->toWellFormedXml($html));

                    $tmp = tempnam(sys_get_temp_dir(), 'docx_') . '.docx';
                    try {
                        \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007')->save($tmp);

                        $filename = Str::slug($this->record->name ?: 'document') . '_' . now()->format('YmdHis') . '.docx';
                        $path = 'document-templates/' . $filename;

                        Storage::disk('public')->put($path, file_get_contents($tmp));

                        $old = $this->record->docx_file;
                        if ($old && $old !== $path && Storage::disk('public')->exists($old)) {
                            Storage::disk('public')->delete($old);
                        }

                        $this->record->update(['docx_file' => $path]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('.docx создан из HTML-шаблона')
                            ->body('Можно открыть «Редактировать в OnlyOffice» или предпросмотр.')
                            ->send();

                        $this->refreshFormData(['docx_file']);
                    } finally {
                        if (file_exists($tmp)) {
                            @unlink($tmp);
                        }
                    }
                }),

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
                ->url(fn () => route('document-templates.preview', $this->record), shouldOpenInNewTab: true)
                ->requiresConfirmation(false),
        ];
    }

    /**
     * Приводим HTML из Tiptap к well-formed XML/XHTML.
     * PhpWord\Shared\Html::addHtml() использует строгий DOMDocument::loadXML(),
     * а Tiptap отдаёт обычный HTML (напр. <br> без закрытия) — это и вызывает
     * "Opening and ending tag mismatch". Прогоняем фрагмент через lenient-парсер
     * (loadHTML), а сериализуем через saveXML, который самозакрывает void-элементы.
     */
    private function toWellFormedXml(string $html): string
    {
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->loadHTML(
            '<?xml encoding="utf-8" ?><div data-root="1">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $node = $doc->getElementsByTagName('div')->item(0);
        if (!$node) {
            return '<p></p>';
        }

        $fragment = '';
        foreach (iterator_to_array($node->childNodes) as $child) {
            $fragment .= $doc->saveXML($child) ?: '';
        }

        libxml_clear_errors();

        return $fragment;
    }
}
