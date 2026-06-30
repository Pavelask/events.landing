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
        Log::info('Yandex webhook: incoming request', [
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'all' => $request->all(),
            'query' => $request->query(),
        ]);

        $secret = $request->header('X-Yandex-Webhook-Secret') ?? $request->query('secret');

        if ($secret !== config('services.webhook.yandex_secret')) {
            Log::warning('Yandex webhook: invalid secret', ['secret' => $secret]);
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $data = $request->all();

        if (empty($data) || (count($data) === 1 && isset($data['secret']))) {
            $rawBody = $request->getContent();
            $jsonData = json_decode($rawBody, true);
            if (is_array($jsonData)) {
                $data = array_merge($data, $jsonData);
            }
        }

        $name = $data['name'] ?? $data['Фамилия, Имя, Отчество'] ?? null;
        $email = $data['email'] ?? $data['Адрес электронной почты'] ?? null;
        $phone = $data['phone'] ?? $data['Номер телефона (мобильный для связи в пути и в г. Сочи)'] ?? null;
        $eventId = is_numeric($data['event_id'] ?? null) ? $data['event_id'] : ($request->query('event_id') ?? null);

        if (!$name) {
            Log::warning('Yandex webhook: missing required fields', ['data' => $data]);
            return response()->json(['error' => 'Missing required fields'], 422);
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

        if ($event->status !== 'published') {
            return response()->json(['message' => 'Event is not published'], 200);
        }

        if (!empty($email)) {
            $exists = Participant::where('event_id', $event->id)
                ->where('email', $email)
                ->exists();

            if ($exists) {
                Log::info('Yandex webhook: duplicate registration', [
                    'event_id' => $event->id,
                    'email' => $email,
                ]);
                return response()->json(['message' => 'Already registered'], 200);
            }
        }

        $participant = Participant::create([
            'event_id' => $event->id,
            'name' => $name,
            'email' => $email ?? null,
            'phone' => $phone ?? null,
            'answers' => $data,
            'source' => 'yandex_form',
        ]);

        $participant->generateCheckinToken();

        Log::info('Yandex webhook: participant created', [
            'participant_id' => $participant->id,
            'event_id' => $event->id,
            'email' => $validated['email'],
        ]);

        return response()->json(['message' => 'Registered successfully'], 200);
    }
}
