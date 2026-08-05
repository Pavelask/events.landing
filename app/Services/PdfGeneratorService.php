<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Participant;
use Illuminate\Support\Facades\Storage;

class PdfGeneratorService
{
    public function generate(Participant $participant, DocumentTemplate $template): string
    {
        $variables = $this->extractVariables($participant, $template);

        $path = $this->generateFromDocx($template, $variables);

        if ($path) {
            return $this->storeOnPrivateDisk($template, $variables, $path);
        }

        $html = $this->buildFullHtml($template, $variables);

        $filename = "{$participant->id}_" . time() . ".pdf";
        $path = "consents/{$filename}";
        $fullPath = Storage::disk('private')->path($path);

        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $pdf = app('laravel-mpdf')->loadHTML($html);
        $pdf->save($fullPath);

        return $path;
    }

    private function generateFromDocx(DocumentTemplate $template, array $variables): ?string
    {
        $docxPath = $this->docxPath($template);

        if (!$docxPath) {
            return null;
        }

        return app(DocxPdfGeneratorService::class)->generate($docxPath, $variables);
    }

    private function docxPath(DocumentTemplate $template): ?string
    {
        if (empty($template->docx_file)) {
            return null;
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($template->docx_file)) {
            return null;
        }

        return $disk->path($template->docx_file);
    }

    private function storeOnPrivateDisk(DocumentTemplate $template, array $variables, string $pdfPath): string
    {
        $filename = md5($template->slug . serialize($variables)) . '_' . time() . ".pdf";
        $path = "consents/{$filename}";
        $fullPath = Storage::disk('private')->path($path);

        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        copy($pdfPath, $fullPath);

        if (file_exists($pdfPath)) {
            @unlink($pdfPath);
            $parentDir = dirname($pdfPath);
            if (is_dir($parentDir)) {
                @rmdir($parentDir);
            }
        }

        return $path;
    }

    public function renderTemplate(DocumentTemplate $template, array $variables): string
    {
        $content = $template->content;

        foreach ($variables as $key => $value) {
            $content = str_replace("{{ {$key} }}", $value ?? '', $content);
        }

        return $content;
    }

    public function getPreview(DocumentTemplate $template): string
    {
        $testData = [
            'full_name' => 'Иванов Иван Иванович',
            'phone' => '+7 (999) 123-45-67',
            'email' => 'test@example.com',
            'event_title' => 'Тестовое мероприятие',
            'event_date' => '01.01.2025',
            'current_date' => now()->format('d.m.Y'),
            'organization_name' => 'Тестовая организация',
            'organization_inn' => '1234567890',
        ];

        foreach (($template->formTemplate?->questions ?? []) as $question) {
            $slug = $question['slug'] ?? null;

            if (!$slug) {
                continue;
            }

            $testData[$slug] = match ($question['type'] ?? 'text') {
                'date' => '10.01.2026',
                'checkbox' => 'Да',
                'select', 'radio' => ($question['options'] ?? [])[0] ?? 'Вариант 1',
                'textarea' => 'Тестовый развернутый ответ участника на вопрос формы',
                default => 'Тестовое значение',
            };
        }

        $docxPdf = $this->generateFromDocx($template, $testData);

        if ($docxPdf) {
            return $docxPdf;
        }

        $html = $this->buildFullHtml($template, $testData);
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_preview_') . '.pdf';

        $pdf = app('laravel-mpdf')->loadHTML($html);
        $pdf->save($tempFile);

        return $tempFile;
    }

    private function buildFullHtml(DocumentTemplate $template, array $variables): string
    {
        $content = $this->renderTemplate($template, $variables);

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12pt; line-height: 1.5; }
    </style>
</head>
<body>
    {$content}
</body>
</html>
HTML;
    }

    private function extractVariables(Participant $participant, ?DocumentTemplate $template = null): array
    {
        $answers = $participant->answers ?? [];

        $variables = [
            'full_name' => $participant->name ?? $answers['full_name'] ?? '',
            'phone' => $participant->phone ?? $answers['phone'] ?? '',
            'email' => $participant->email ?? $answers['email'] ?? '',
            'event_title' => $participant->event?->title ?? '',
            'event_date' => $participant->event?->start_date?->format('d.m.Y') ?? '',
            'current_date' => now()->format('d.m.Y'),
            'organization_name' => config('app.organization_name', ''),
            'organization_inn' => config('app.organization_inn', ''),
        ];

        foreach (($template?->formTemplate?->questions ?? []) as $question) {
            $slug = $question['slug'] ?? null;

            if ($slug) {
                $variables[$slug] = $answers[$slug] ?? '';
            }
        }

        return $variables;
    }
}
