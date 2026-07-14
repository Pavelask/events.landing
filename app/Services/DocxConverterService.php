<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;

class DocxConverterService
{
    private array $placeholderMap = [
        '/ф\.?\s*и\.?\s*о\.?\s*полностью/i' => 'full_name',
        '/фамилия,?\s*имя,?\s*отчество/i' => 'full_name',
        '/фамилия/i' => 'full_name',
        '/серия\s*_{3,}/' => 'passport_series',
        '/серия/i' => 'passport_series',
        '/номер\s*_{3,}/' => 'passport_number',
        '/номер/i' => 'passport_number',
        '/выдан\s*_{3,}/' => 'passport_issued_by',
        '/зарегистрированный\(ая\)\s*по\s*адресу/i' => 'registration_address',
        '/по\s*адресу/i' => 'registration_address',
        '/номер\s*телефона/i' => 'phone',
        '/адрес\s*электронной\s*почты/i' => 'email',
        '/название\s*мероприятия/i' => 'event_title',
        '/мероприяти[ея]/i' => 'event_title',
        '/слёт/i' => 'event_title',
    ];

    public function convertToHtml(string $docxPath): string
    {
        $phpWord = IOFactory::load($docxPath);

        $html = '';
        foreach ($phpWord->getSections() as $section) {
            $html .= $this->convertSection($section);
        }

        // Sanitize: remove invalid UTF-8 characters and control characters
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        $html = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $html);
        $html = preg_replace('/\p{C}+/u', '', $html);

        return $html;
    }

    private function convertSection($section): string
    {
        $html = '';
        foreach ($section->getElements() as $element) {
            $html .= $this->convertElement($element);
        }
        return $html;
    }

    private function convertElement($element): string
    {
        $class = get_class($element);

        return match ($class) {
            'PhpOffice\PhpWord\Element\TextRun' => $this->convertTextRun($element),
            'PhpOffice\PhpWord\Element\Paragraph' => $this->convertParagraph($element),
            'PhpOffice\PhpWord\Element\Table' => $this->convertTable($element),
            'PhpOffice\PhpWord\Element\Section' => $this->convertSection($element),
            default => '',
        };
    }

    private function convertTextRun($textRun): string
    {
        $text = '';
        foreach ($textRun->getElements() as $element) {
            if (method_exists($element, 'getText')) {
                $text .= e($element->getText());
            }
        }

        $style = $textRun->getFontStyle();
        if ($style && $style->isBold()) {
            $text = "<strong>{$text}</strong>";
        }

        return $text;
    }

    private function convertParagraph($paragraph): string
    {
        $text = '';
        foreach ($paragraph->getElements() as $element) {
            $text .= $this->convertElement($element);
        }

        $style = $paragraph->getStyle();
        $align = '';
        if ($style && method_exists($style, 'getAlignment')) {
            $alignment = $style->getAlignment();
            $align = match ($alignment) {
                'center' => ' style="text-align: center;"',
                'right' => ' style="text-align: right;"',
                default => '',
            };
        }

        return "<p{$align}>{$text}</p>\n";
    }

    private function convertTable($table): string
    {
        $html = '<table style="width: 100%; border-collapse: collapse; margin: 10px 0;">';

        foreach ($table->getRows() as $row) {
            $html .= '<tr>';
            foreach ($row->getCells() as $cell) {
                $cellText = '';
                foreach ($cell->getElements() as $element) {
                    $cellText .= $this->convertElement($element);
                }
                $html .= "<td style=\"border: 1px solid #ccc; padding: 5px;\">{$cellText}</td>";
            }
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

    public function detectPlaceholders(string $html): array
    {
        $found = [];

        foreach ($this->placeholderMap as $pattern => $placeholder) {
            if (preg_match($pattern, $html)) {
                $found[$placeholder] = $placeholder;
            }
        }

        return array_values(array_unique($found));
    }

    public function applyPlaceholders(string $html, array $mapping = []): string
    {
        if (empty($mapping)) {
            $mapping = $this->placeholderMap;
        }

        foreach ($mapping as $pattern => $placeholder) {
            $html = preg_replace($pattern, "{{ {$placeholder} }}", $html);
        }

        // Final sanitization
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        $html = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $html);

        return $html;
    }
}
