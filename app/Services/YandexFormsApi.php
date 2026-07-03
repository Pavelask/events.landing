<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YandexFormsApi
{
    private ?string $token;
    private string $baseUrl = 'https://api.forms.yandex.ru/public/api/v1';

    public function __construct()
    {
        $this->token = config('services.yandex.token');
    }

    public function createAnswer(string $formId, array $data): ?array
    {
        if (!$this->token) {
            Log::error('Yandex Forms API: token not configured');
            return null;
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->post("{$this->baseUrl}/forms/{$formId}/answers", $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Yandex Forms API: createAnswer failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Yandex Forms API: createAnswer exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function getAnswer(string $formId, string $answerId): ?array
    {
        if (!$this->token) {
            Log::error('Yandex Forms API: token not configured');
            return null;
        }

        $cacheKey = "yandex_answer_{$formId}_{$answerId}";

        return Cache::remember($cacheKey, 600, function () use ($formId, $answerId) {
            try {
                $response = Http::withToken($this->token)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/forms/{$formId}/answers/{$answerId}");

                if ($response->successful()) {
                    return $response->json();
                }

                Log::warning('Yandex Forms API: getAnswer failed', [
                    'status' => $response->status(),
                    'answer_id' => $answerId,
                ]);

                return null;
            } catch (\Exception $e) {
                Log::error('Yandex Forms API: getAnswer exception', [
                    'message' => $e->getMessage(),
                    'answer_id' => $answerId,
                ]);
                return null;
            }
        });
    }

    public function getAnswers(string $formId, array $filters = []): array
    {
        if (!$this->token) {
            Log::error('Yandex Forms API: token not configured');
            return [];
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get("{$this->baseUrl}/forms/{$formId}/answers", $filters);

            if ($response->successful()) {
                return $response->json('data', []);
            }

            Log::warning('Yandex Forms API: getAnswers failed', [
                'status' => $response->status(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Yandex Forms API: getAnswers exception', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function clearCache(string $answerId): void
    {
        $keys = Cache::tags([])->getKeys();

        foreach ($keys as $key) {
            if (str_contains($key, $answerId)) {
                Cache::forget($key);
            }
        }
    }
}
