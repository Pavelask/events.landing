<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $name = null;
        $email = null;
        $phone = null;
        $eventId = $request->query('event_id');

        foreach ($answers as $slug => $item) {
            $value = $item['value'] ?? null;
            $typeSlug = $item['question']['answer_type']['slug'] ?? '';

            if ($value === null || $value === '') {
                continue;
            }

            if ($typeSlug === 'answer_number' || str_contains($slug, 'integer')) {
                $eventId = $eventId ?? $value;
            } elseif ($typeSlug === 'answer_phone') {
                $phone = $value;
            } elseif ($typeSlug === 'answer_non_profile_email' || $typeSlug === 'answer_email') {
                $email = $value;
            } elseif ($typeSlug === 'answer_short_text' || $typeSlug === 'answer_text') {
                if (!$name) {
                    $name = $value;
                }
            }
        }

        if (!$name) {
            Log::warning('Yandex webhook: no name found', ['answers' => $answers]);
            return response()->json(['error' => 'Name is required'], 422);
        }

        if ($eventId && is_numeric($eventId)) {
            $event = Event::find($eventId);
        } else {
            $event = Event::where('status', 'published')->first();
        }

        if (!$event) {
            Log::warning('Yandex webhook: event not found', ['event_id' => $eventId]);
            return response()->json(['error' => 'Event not found'], 404);
        }

        if (!empty($email)) {
            $exists = Participant::where('event_id', $event->id)
                ->where('email', $email)
                ->exists();

            if ($exists) {
                Log::info('Yandex webhook: duplicate', ['email' => $email]);
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

        Log::info('Yandex webhook: created', ['id' => $participant->id, 'email' => $email]);

        return response()->json(['message' => 'Registered successfully'], 200);
    }
}
