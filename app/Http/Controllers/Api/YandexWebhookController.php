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
        $secret = $request->header('X-Yandex-Webhook-Secret') ?? $request->query('secret');

        if ($secret !== config('services.webhook.yandex_secret')) {
            Log::warning('Yandex webhook: invalid secret', ['secret' => $secret]);
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

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
