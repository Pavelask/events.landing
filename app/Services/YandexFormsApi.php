<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YandexFormsApi
{
    private ?string $token;
    private ?string $orgId;
    private string $baseUrl = 'https://api.forms.yandex.net/v1';

    public function __construct()
    {
        $this->token = config('services.yandex.token');
        $this->orgId = config('services.yandex.org_id');
    }

    private function headers(): array
    {
        $headers = ['Authorization' => 'OAuth ' . $this->token];
        if ($this->orgId) {
            $headers['X-Cloud-Org-Id'] = $this->orgId;
        }
        return $headers;
    }

    public function createAnswer(string $formId, array $data): ?array
    {
        if (!$this->token) {
            Log::error('Yandex Forms API: token not configured');
            return null;
        }

        try {
            $response = Http::withHeaders($this->headers())
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
                $response = Http::withHeaders($this->headers())
                    ->timeout(30)
                    ->get("{$this->baseUrl}/surveys/{$formId}/answers/{$answerId}");

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
            $url = "{$this->baseUrl}/surveys/{$formId}/answers";
            $params = array_merge(['page_size' => 100], $filters);

            Log::info('Yandex Forms API: requesting', ['url' => $url, 'params' => $params]);

            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->get($url, $params);

            Log::info('Yandex Forms API: response', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500),
            ]);

            if ($response->successful()) {
                $json = $response->json();
                return $json['answers'] ?? [];
            }

            Log::warning('Yandex Forms API: getAnswers failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Yandex Forms API: getAnswers exception', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function findAnswersByEmail(string $formId, string $email): array
    {
        $allAnswers = $this->getAnswers($formId);

        return array_filter($allAnswers, function ($answer) use ($email) {
            $answerEmail = $answer['answerer']['email'] ?? $answer['email'] ?? '';
            return strtolower($answerEmail) === strtolower($email);
        });
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
