<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Participant;
use Illuminate\Support\Facades\Storage;

class PdfGeneratorService
{
    public function generate(Participant $participant, DocumentTemplate $template): string
    {
        $variables = $this->extractVariables($participant);
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
            'passport_series' => '1234',
            'passport_number' => '567890',
            'passport_issued_by' => 'ОВД г. Москвы, 01.01.2020',
            'passport_issue_date' => '01.01.2020',
            'registration_address' => 'г. Москва, ул. Тестовая, д. 1, кв. 1',
            'phone' => '+7 (999) 123-45-67',
            'email' => 'test@example.com',
            'event_title' => 'Тестовое мероприятие',
            'event_date' => '01.01.2025',
            'current_date' => now()->format('d.m.Y'),
            'organization_name' => 'Тестовая организация',
            'organization_inn' => '1234567890',
        ];

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

    private function extractVariables(Participant $participant): array
    {
        $answers = $participant->answers ?? [];

        return [
            'full_name' => $participant->name ?? $answers['full_name'] ?? '',
            'passport_series' => $answers['passport_series'] ?? '',
            'passport_number' => $answers['passport_number'] ?? '',
            'passport_issued_by' => $answers['passport_issued_by'] ?? '',
            'passport_issue_date' => $answers['passport_issue_date'] ?? '',
            'registration_address' => $answers['registration_address'] ?? '',
            'phone' => $participant->phone ?? $answers['phone'] ?? '',
            'email' => $participant->email ?? $answers['email'] ?? '',
            'event_title' => $participant->event?->title ?? '',
            'event_date' => $participant->event?->start_date?->format('d.m.Y') ?? '',
            'current_date' => now()->format('d.m.Y'),
            'organization_name' => config('app.organization_name', ''),
            'organization_inn' => config('app.organization_inn', ''),
        ];
    }
}
