<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DocxPdfGeneratorService
{
    /**
     * Подставляет переменные в .docx и конвертирует в PDF через LibreOffice.
     * Возвращает путь к готовому PDF или null при ошибке.
     */
    public function generate(string $docxPath, array $variables): ?string
    {
        $editedDocx = $this->substitute($docxPath, $variables);

        if (!$editedDocx) {
            return null;
        }

        $outDir = sys_get_temp_dir() . '/openoffice_pdf_' . uniqid();

        if (!@mkdir($outDir, 0755, true)) {
            @unlink($editedDocx);
            return null;
        }

        try {
            return $this->convertToPdf($editedDocx, $outDir);
        } finally {
            @unlink($editedDocx);
        }
    }

    /**
     * Заменяет {{ var }} в word/document.xml, header* и footer* на значения.
     * Возвращает путь к изменённому .docx.
     */
    public function substitute(string $docxPath, array $variables): ?string
    {
        $tmpDir = sys_get_temp_dir() . '/docx_subst_' . uniqid();

        if (!@mkdir($tmpDir, 0755, true)) {
            return null;
        }

        $zip = new \ZipArchive();

        if ($zip->open($docxPath) !== true) {
            $this->removeDir($tmpDir);
            return null;
        }

        $zip->extractTo($tmpDir);
        $zip->close();

        $xmlFiles = ['document.xml'];

        foreach (glob("{$tmpDir}/word/header*.xml") as $file) {
            $xmlFiles[] = basename($file);
        }

        foreach (glob("{$tmpDir}/word/footer*.xml") as $file) {
            $xmlFiles[] = basename($file);
        }

        foreach ($xmlFiles as $relative) {
            $path = "{$tmpDir}/word/{$relative}";

            if (!is_file($path)) {
                continue;
            }

            $xml = file_get_contents($path);

            if ($relative === 'document.xml') {
                $xml = $this->forceA4PageSize($xml);
            }

            file_put_contents($path, $this->substituteXml($xml, $variables));
        }

        $newPath = sys_get_temp_dir() . '/docx_subst_' . uniqid() . '.docx';

        $newZip = new \ZipArchive();

        if ($newZip->open($newPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            $this->removeDir($tmpDir);
            return null;
        }

        $this->addDirectoryToZip($newZip, $tmpDir, $tmpDir);
        $newZip->close();

        $this->removeDir($tmpDir);

        return $newPath;
    }

    private function substituteXml(string $xml, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $placeholder = '{{ ' . $key . ' }}';

            $escaped = htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $escaped = str_replace(["\r\n", "\r", "\n"], '<w:br/>', $escaped);

            if (str_contains($xml, $placeholder)) {
                $xml = str_replace($placeholder, $escaped, $xml);
                continue;
            }

            $xml = $this->replaceSplitPlaceholder($xml, $placeholder, $escaped);
        }

        return $xml;
    }

    /**
     * Приводим страницу документа к формату A4 (210 x 297 мм = 11906 x 16838 twips),
     * сохраняя ориентацию (portrait/landscape) и остальные атрибуты <w:pgSz>.
     */
    private function forceA4PageSize(string $xml): string
    {
        return preg_replace(
            '~(<w:pgSz\b[^>]*?w:w=")\d+(?:\.\d+)?("[^>]*?w:h=")\d+(?:\.\d+)?("[^>]*?(?:/>|>))~',
            '${1}11906${2}16838${3}',
            $xml
        ) ?? $xml;
    }

    /**
     * Плейсхолдер в Word может быть разбит по нескольких <w:r>/<w:t>.
     * Строим regex, допускающий теги между символами плейсхолдера.
     */
    private function replaceSplitPlaceholder(string $xml, string $placeholder, string $replacement): string
    {
        $chars = preg_split('//u', $placeholder, -1, PREG_SPLIT_NO_EMPTY);

        if (empty($chars)) {
            return $xml;
        }

        foreach ($chars as &$char) {
            $char = preg_quote($char, '~');
        }
        unset($char);

        $between = '(?:<[^>]*>)*';

        return preg_replace('~' . implode($between, $chars) . '~u', $replacement, $xml) ?? $xml;
    }

    public function convertToPdf(string $docxPath, string $outDir): ?string
    {
        $soffice = $this->sofficePath();

        if (!$soffice) {
            Log::error('DocxPdfGenerator: LibreOffice (soffice) not found');
            return null;
        }

        $command = escapeshellarg($soffice)
            . ' --headless --norestore --convert-to pdf'
            . ' --outdir ' . escapeshellarg(realpath($outDir))
            . ' ' . escapeshellarg($docxPath);

        $output = [];
        $exitCode = 0;

        exec($command . ' 2>&1', $output, $exitCode);

        if ($exitCode !== 0) {
            Log::error('DocxPdfGenerator: LibreOffice conversion failed', [
                'command' => $command,
                'output' => implode(' | ', $output),
            ]);
            return null;
        }

        $pdf = rtrim(realpath($outDir), '/') . '/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';

        return is_file($pdf) ? $pdf : null;
    }

    private function sofficePath(): ?string
    {
        $configured = config('services.libreoffice.path');

        if ($configured && is_executable($configured)) {
            return $configured;
        }

        $candidates = [
            '/Applications/LibreOffice.app/Contents/MacOS/soffice',
            '/usr/bin/soffice',
            '/usr/local/bin/soffice',
            '/usr/bin/libreoffice',
            '/opt/libreoffice/program/soffice',
            '/opt/libreoffice${VERSION}/program/soffice',
            (function () {
                $which = shell_exec('command -v soffice 2>/dev/null');
                return $which ? trim($which) : null;
            })(),
        ];

        foreach (array_filter($candidates) as $candidate) {
            if (is_file($candidate) && is_executable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function addDirectoryToZip(\ZipArchive $zip, string $dir, string $baseDir): void
    {
        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;

            if (is_dir($path)) {
                $zip->addEmptyDir(ltrim(substr($path, strlen($baseDir)), '/'));
                $this->addDirectoryToZip($zip, $path, $baseDir);
            } elseif (is_file($path)) {
                $localName = ltrim(substr($path, strlen($baseDir)), '/');
                $zip->addFile($path, $localName);
            }
        }
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }

        @rmdir($dir);
    }
}