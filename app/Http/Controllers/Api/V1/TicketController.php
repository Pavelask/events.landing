<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\AnonParticipant;
use App\Models\Participant;
use App\Services\YandexFormsApi;
use Illuminate\Http\JsonResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function show(string $token, YandexFormsApi $yandexApi): TicketResource|JsonResponse
    {
        $participant = Participant::with('event')
            ->where('checkin_token', $token)
            ->first();

        if ($participant) {
            return new TicketResource(
                $participant,
                $participant->name,
                $participant->email,
                'classic',
            );
        }

        $anonParticipant = AnonParticipant::with('event')
            ->where('checkin_token', $token)
            ->first();

        if (! $anonParticipant) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $formId = $anonParticipant->event?->formTemplate?->yandex_form_id;
        $answer = $formId && $anonParticipant->answer_id
            ? $yandexApi->getAnswer($formId, $anonParticipant->answer_id)
            : [];
        $answerData = $answer['data'] ?? [];

        $name = 'Участник';
        $email = '';
        foreach ($answerData as $item) {
            $label = mb_strtolower($item['label'] ?? '');
            if (in_array($label, ['фио участника', 'имя', 'name', 'фио'], true)) {
                $name = $item['value'] ?? $name;
            }
            if (in_array($label, ['почта', 'email', 'электронная почта'], true)) {
                $email = $item['value'] ?? $email;
            }
        }

        return new TicketResource($anonParticipant, $name, $email, 'anon');
    }

    public function qr(string $token): Response
    {
        $exists = Participant::query()->where('checkin_token', $token)->exists()
            || AnonParticipant::query()->where('checkin_token', $token)->exists();

        abort_unless($exists, 404);

        $png = QrCode::format('png')
            ->size(400)
            ->margin(2)
            ->errorCorrection('M')
            ->generate(route('checkin.handle', $token));

        return response($png)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
