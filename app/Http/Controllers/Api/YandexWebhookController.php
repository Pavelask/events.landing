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

        $hasAnswerId = DB::select("SHOW COLUMNS FROM participants LIKE 'answer_id'");

        if (!empty($hasAnswerId)) {
            return $this->handleNewSchema($jsonData, $answers, $request);
        }

        return $this->handleOldSchema($jsonData, $answers, $request);
    }

    private function handleOldSchema(array $jsonData, array $answers, Request $request): JsonResponse
    {
        $name = null;
        $email = null;
        $phone = null;
        $eventId = $request->query('event_id');

        foreach ($answers as $slug => $item) {
            $value = is_array($item) ? ($item['value'] ?? null) : $item;
            $typeSlug = is_array($item) ? ($item['question']['answer_type']['slug'] ?? '') : '';

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

        if (!$name) {
            Log::warning('Yandex webhook: no name found', ['answers' => $answers]);
            return response()->json(['error' => 'Name is required'], 422);
        }

        $event = $this->resolveEvent($eventId);
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        if (!empty($email)) {
            $exists = Participant::where('event_id', $event->id)->where('email', $email)->exists();
            if ($exists) {
                return response()->json(['message' => 'Already registered'], 200);
            }
        }

        $participant = Participant::create([
            'event_id' => $event->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'answers' => $jsonData,
            'source' => 'yandex_form',
        ]);

        $participant->generateCheckinToken();

        Log::info('Yandex webhook: created (old schema)', ['id' => $participant->id, 'email' => $email]);

        return response()->json(['message' => 'Registered successfully'], 200);
    }

    private function handleNewSchema(array $jsonData, array $answers, Request $request): JsonResponse
    {
        $answerId = $jsonData['answer']['id'] ?? null;
        if (!$answerId) {
            return response()->json(['error' => 'No answer_id'], 422);
        }

        $formId = $request->query('form_id') ?? $jsonData['form']['id'] ?? null;
        $eventId = $request->query('event_id');

        $event = $this->resolveEvent($eventId, $formId);
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $exists = Participant::where('event_id', $event->id)
            ->where('answer_id', (string) $answerId)->exists();

        if ($exists) {
            return response()->json(['message' => 'Already registered'], 200);
        }

        Participant::create([
            'event_id' => $event->id,
            'answer_id' => (string) $answerId,
            'checkin_token' => Str::random(40),
            'status' => 'registered',
        ]);

        Log::info('Yandex webhook: created (new schema)', ['answer_id' => $answerId]);

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
