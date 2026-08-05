<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            $headers['X-Org-Id'] = $this->orgId;
        }
        return $headers;
    }

    public function getForm(string $formId): ?array
    {
        if (!$this->token) {
            Log::error('Yandex Forms API: token not configured');
            return null;
        }

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->get("{$this->baseUrl}/surveys/{$formId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Yandex Forms API: getForm failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'form_id' => $formId,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Yandex Forms API: getForm exception', [
                'message' => $e->getMessage(),
                'form_id' => $formId,
            ]);
            return null;
        }
    }

    public function getQuestions(string $formId): array
    {
        if (!$this->token) {
            Log::error('Yandex Forms API: token not configured');
            return [];
        }

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->get("{$this->baseUrl}/surveys/{$formId}/questions");

            if ($response->successful()) {
                $items = [];

                foreach ($response->json()['pages'] ?? [] as $page) {
                    foreach ($page['items'] ?? [] as $item) {
                        $items[] = $item;
                    }
                }

                return $items;
            }

            Log::warning('Yandex Forms API: getQuestions failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'form_id' => $formId,
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Yandex Forms API: getQuestions exception', [
                'message' => $e->getMessage(),
                'form_id' => $formId,
            ]);
            return [];
        }
    }

    public function mapQuestions(?array $questions): array
    {
        $mapped = [];

        foreach ($questions ?? [] as $question) {
            $this->collectQuestion($question, $mapped);
        }

        return $mapped;
    }

    private function collectQuestion(array $question, array &$mapped): void
    {
        if (($question['type'] ?? '') === 'series') {
            foreach ($question['items'] ?? [] as $inner) {
                $this->collectQuestion($inner, $mapped);
            }

            return;
        }

        if (!empty($question['hidden'])) {
            return;
        }

        $type = $this->mapQuestionType($question);

        if ($type === null) {
            return;
        }

        $label = $this->questionTitle($question['label'] ?? '');
        $slug = $this->uniqueSlug($question['slug'] ?? $question['label'] ?? 'field', $mapped);

        $mapped[] = [
            'label' => $label,
            'type' => $type,
            'required' => $this->isRequired($question['validators'] ?? []),
            'options' => $this->questionOptions($question, $type),
            'searchable' => false,
            'slug' => $slug,
        ];
    }

    private function mapQuestionType(array $question): ?string
    {
        return match ($question['type'] ?? '') {
            'string' => !empty($question['multiline']) ? 'textarea' : 'text',
            'integer', 'number', 'series_integer' => 'text',
            'date' => 'date',
            'enum' => match ($question['widget'] ?? 'dropdown') {
                'radio' => 'radio',
                'checkbox' => 'checkbox',
                default => 'select',
            },
            'boolean' => 'checkbox',
            default => null,
        };
    }

    private function isRequired(array $validators): bool
    {
        foreach ($validators as $validator) {
            if (($validator['type'] ?? '') === 'required') {
                return true;
            }
        }

        return false;
    }

    private function questionTitle(string | array $title): string
    {
        if (is_array($title)) {
            $parts = [];

            foreach ($title as $part) {
                if (isset($part['text']) && $part['text'] !== '') {
                    $parts[] = $part['text'];
                }
            }

            return trim(implode(' ', $parts)) ?: 'Без названия';
        }

        return trim($title) ?: 'Без названия';
    }

    private function questionOptions(array $question, string $type): array
    {
        if (!in_array($type, ['select', 'radio', 'checkbox'], true)) {
            return [];
        }

        $options = [];

        foreach ($question['items'] ?? $question['choices'] ?? [] as $item) {
            $label = $this->questionTitle($item['label'] ?? $item['answer'] ?? '');
            if ($label !== 'Без названия') {
                $options[] = $label;
            }
        }

        return array_values(array_unique($options));
    }

    private function uniqueSlug(string $id, array $existing): string
    {
        $slug = Str::lower(trim($id));
        $slug = preg_replace('/[^a-z0-9_\-]+/', '-', $slug);
        $slug = trim($slug, '-');

        if ($slug === '') {
            $slug = 'field';
        }

        $base = $slug;
        $i = 2;

        while (in_array($slug, array_column($existing, 'slug'), true)) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function createAnswer(string $formId, array $data): ?array
    {
        if (!$this->token) {
            Log::error('Yandex Forms API: token not configured');
            return null;
        }

        try {
            $response = Http::withHeaders(array_merge($this->headers(), [
                'Content-Type' => 'application/json',
            ]))
            ->withBody(json_encode($data), 'application/json')
            ->timeout(30)
            ->post("{$this->baseUrl}/surveys/{$formId}/form");

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Yandex Forms API: createAnswer failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'sent_data' => $data,
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

        $cacheKey = "yandex_answer_{$answerId}";

        return Cache::remember($cacheKey, 600, function () use ($answerId) {
            try {
                $response = Http::withHeaders($this->headers())
                    ->timeout(30)
                    ->get("{$this->baseUrl}/answers", [
                        'answer_id' => $answerId,
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::warning('Yandex Forms API: getAnswer failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
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

            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->get($url, $params);

            if ($response->successful()) {
                $json = $response->json();
                return $json['answers'] ?? [];
            }

            Log::warning('Yandex Forms API: getAnswers failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'form_id' => $formId,
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Yandex Forms API: getAnswers exception', [
                'message' => $e->getMessage(),
                'form_id' => $formId,
            ]);
            return [];
        }
    }

    public function findAnswersByEmail(string $formId, string $email): array
    {
        $allAnswers = $this->getAnswers($formId);

        return array_filter($allAnswers, function ($answer) use ($email) {
            $answerData = $answer['data'] ?? [];
            foreach ($answerData as $item) {
                $label = mb_strtolower($item['label'] ?? '');
                if (in_array($label, ['email', 'электронная почта', 'е-мейл'])) {
                    return mb_strtolower($item['value'] ?? '') === mb_strtolower($email);
                }
            }
            return false;
        });
    }

    public function clearCache(string $answerId): void
    {
        Cache::forget("yandex_answer_{$answerId}");
    }

    public function normalizeAnswers(array $data): array
    {
        $normalized = [];

        foreach ($data as $slug => $item) {
            if (!is_array($item) && !is_string($item)) {
                continue;
            }

            $value = is_string($item) ? $item : ($item['value'] ?? null);

            if (is_array($value)) {
                $parts = [];

                foreach ($value as $part) {
                    if (is_array($part)) {
                        $parts[] = $part['text'] ?? $part['key'] ?? '';
                    } else {
                        $parts[] = $part;
                    }
                }

                $value = implode(', ', array_filter($parts));
            }

            if ($value === null || $value === '') {
                continue;
            }

            $normalized[(string) $slug] = trim((string) $value);
        }

        return $normalized;
    }
}
