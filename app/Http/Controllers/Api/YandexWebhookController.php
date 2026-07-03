<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class YandexWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $rawBody = $request->getContent();
        $jsonData = json_decode($rawBody, true);

        Log::info('Yandex webhook: incoming', [
            'body' => $jsonData,
            'query' => $request->query(),
        ]);

        $secret = $request->header('X-Yandex-Webhook-Secret') ?? $request->query('secret');

        if ($secret !== config('services.webhook.yandex_secret')) {
            Log::warning('Yandex webhook: invalid secret');
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $answers = $jsonData['answer']['data'] ?? $jsonData['data'] ?? null;

        if (!$answers) {
            Log::warning('Yandex webhook: no answer data', ['raw' => $jsonData]);
            return response()->json(['error' => 'No answer data'], 422);
        }

        // Extract answer_id
        $answerId = $jsonData['answer']['id'] ?? null;

        // Extract personal data from answers
        $name = null;
        $email = null;
        $phone = null;
        $eventId = $request->query('event_id');

        // Answers can be array (numeric keys) or assoc object
        $items = is_array($answers) ? $answers : [];

        foreach ($items as $slug => $item) {
            if (!is_array($item)) continue;

            $value = $item['value'] ?? null;
            $typeSlug = $item['question']['answer_type']['slug'] ?? '';

            if ($value === null || $value === '') continue;

            if ($typeSlug === 'answer_phone') {
                $phone = $value;
            } elseif (in_array($typeSlug, ['answer_non_profile_email', 'answer_email'])) {
                $email = $value;
            } elseif (in_array($typeSlug, ['answer_short_text', 'answer_text', 'answer_name'])) {
                if (!$name) $name = $value;
            } elseif ($typeSlug === 'answer_number' || str_contains((string) $slug, 'integer')) {
                $eventId = $eventId ?? $value;
            }
        }

        // Resolve event
        $formId = $request->query('form_id') ?? $jsonData['survey']['id'] ?? $jsonData['form']['id'] ?? null;
        $event = $this->resolveEvent($eventId, $formId);
        if (!$event) {
            Log::warning('Yandex webhook: event not found', ['event_id' => $eventId, 'form_id' => $formId]);
            return response()->json(['error' => 'Event not found'], 404);
        }

        // Check duplicate by answer_id or email
        if ($answerId) {
            $exists = Participant::where('event_id', $event->id)
                ->where('answer_id', (string) $answerId)->exists();
            if ($exists) {
                return response()->json(['message' => 'Already registered'], 200);
            }
        }

        if (!empty($email)) {
            $exists = Participant::where('event_id', $event->id)
                ->where('email', $email)->exists();
            if ($exists) {
                return response()->json(['message' => 'Already registered'], 200);
            }
        }

        // Build participant data
        $data = [
            'event_id' => $event->id,
            'checkin_token' => Str::random(40),
            'status' => 'registered',
        ];

        if ($answerId) {
            $data['answer_id'] = (string) $answerId;
        }
        if ($name) {
            $data['name'] = $name;
        }
        if ($email) {
            $data['email'] = $email;
        }
        if ($phone) {
            $data['phone'] = $phone;
        }

        $participant = Participant::create($data);

        Log::info('Yandex webhook: created', [
            'id' => $participant->id,
            'name' => $name,
            'email' => $email,
            'answer_id' => $answerId,
        ]);

        return response()->json(['message' => 'Registered successfully'], 200);
    }

    private function resolveEvent(?string $eventId = null, ?string $formId = null): ?Event
    {
        if ($eventId && is_numeric($eventId)) {
            return Event::find($eventId);
        }
        if ($formId) {
            return Event::where('yandex_form_id', $formId)->first();
        }
        return Event::where('status', 'published')->first();
    }
}
