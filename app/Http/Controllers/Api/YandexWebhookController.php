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
        $eventId = $data['event_id'] ?? null;

        if (!$eventId || !$name) {
            Log::warning('Yandex webhook: missing required fields', ['data' => $data]);
            return response()->json(['error' => 'Missing required fields'], 422);
        }

        $validated = [
            'event_id' => $eventId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ];

        $event = Event::findOrFail($validated['event_id']);

        if ($event->status !== 'published') {
            return response()->json(['message' => 'Event is not published'], 200);
        }

        if (!empty($validated['email'])) {
            $exists = Participant::where('event_id', $event->id)
                ->where('email', $validated['email'])
                ->exists();

            if ($exists) {
                Log::info('Yandex webhook: duplicate registration', [
                    'event_id' => $event->id,
                    'email' => $validated['email'],
                ]);
                return response()->json(['message' => 'Already registered'], 200);
            }
        }

        $participant = Participant::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'answers' => $request->all(),
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
