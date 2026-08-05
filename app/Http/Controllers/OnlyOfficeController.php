<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Services\OnlyOfficeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OnlyOfficeController extends Controller
{
    /**
     * Отдаём docx-файл шаблона по подписанному URL.
     * Запрос приходит от самого Document Server (without сессии браузера).
     */
    public function download(DocumentTemplate $documentTemplate): StreamedResponse
    {
        if (empty($documentTemplate->docx_file)) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($documentTemplate->docx_file)) {
            abort(404);
        }

        return response()->streamDownload(function () use ($disk, $documentTemplate) {
            echo $disk->get($documentTemplate->docx_file);
        }, basename($documentTemplate->docx_file), [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    /**
     * Callback от OnlyOffice после сохранения/правки документа.
     * Документ автоматически сохраняется обратно в docx_file.
     */
    public function callback(Request $request, DocumentTemplate $documentTemplate, OnlyOfficeService $service): \Illuminate\Http\JsonResponse
    {
        $payload = $request->all();
        $bearer = $request->header('Authorization');

        $token = null;
        if ($bearer && preg_match('/Bearer\s+(.+)$/i', $bearer, $m)) {
            $token = $m[1];
        } elseif (!empty($payload['token'])) {
            $token = $payload['token'];
        }

        if (!$service->validateCallback($token)) {
            Log::warning('OnlyOffice callback: invalid JWT', ['template' => $documentTemplate->getKey()]);

            return response()->json(['error' => 1], 403);
        }

        $status = (int) ($payload['status'] ?? 0);

        // Статусы OnlyOffice: 1 = ок, правка сохранилась и есть ссылка на файл.
        // 0/2/3/4/6/7 — без файла, просто подтверждаем получение.
        if (in_array($status, [1, 2, 3, 4, 6, 7], true)) {
            return response()->json(['error' => 0]);
        }

        // Статус 2 — документ закрыт/изменён, в url idёт сохранённый файл.
        if ($status === 2 && !empty($payload['url'])) {
            try {
                $content = Http::timeout(60)->get($payload['url'])->body();

                if (empty($content)) {
                    throw new \RuntimeException('Downloaded file is empty');
                }

                $disk = Storage::disk('public');
                $filename = sprintf(
                    '%s_%s.docx',
                    \Illuminate\Support\Str::slug($documentTemplate->name ?: 'document'),
                    now()->format('YmdHis')
                );
                $path = 'document-templates/' . $filename;

                $disk->put($path, $content);

                // Удаляем предыдущий файл, если не совпадает.
                $old = $documentTemplate->docx_file;
                if ($old && $old !== $path && $disk->exists($old)) {
                    $disk->delete($old);
                }

                $documentTemplate->update(['docx_file' => $path]);

                Log::info('OnlyOffice: document updated', [
                    'template' => $documentTemplate->getKey(),
                    'path' => $path,
                ]);
            } catch (\Throwable $e) {
                Log::error('OnlyOffice callback: save failed', [
                    'template' => $documentTemplate->getKey(),
                    'error' => $e->getMessage(),
                ]);

                return response()->json(['error' => 1], 422);
            }
        }

        return response()->json(['error' => 0]);
    }
}