<?php

namespace App\Http\Controllers;

use App\Models\AnonParticipant;
use App\Models\Participant;
use App\Services\YandexFormsApi;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function show(string $token, YandexFormsApi $yandexApi)
    {
        $participant = Participant::with('event')
            ->where('checkin_token', $token)
            ->first();

        $type = 'classic';
        $participantData = null;
        $eventName = '';

        if ($participant) {
            $eventName = $participant->event->title ?? '';
            $participantData = [
                'name' => $participant->name,
                'email' => $participant->email,
                'event_title' => $eventName,
            ];
        } else {
            $anonParticipant = AnonParticipant::with('event')
                ->where('checkin_token', $token)
                ->firstOrFail();

            $eventName = $anonParticipant->event->title ?? '';
            $type = 'anon';

            $formId = $anonParticipant->event->formTemplate->yandex_form_id ?? null;
            $answer = $yandexApi->getAnswer($formId, $anonParticipant->answer_id);

            $answerData = $answer['data'] ?? [];
            $name = 'Участник';
            $email = '';
            foreach ($answerData as $item) {
                $label = mb_strtolower($item['label'] ?? '');
                if (in_array($label, ['фио участника', 'имя', 'name', 'фио'])) {
                    $name = $item['value'] ?? 'Участник';
                }
                if (in_array($label, ['почта', 'email', 'электронная почта'])) {
                    $email = $item['value'] ?? '';
                }
            }
            $participantData = [
                'name' => $name,
                'email' => $email,
                'event_title' => $eventName,
            ];

            $participant = $anonParticipant;
        }

        $ticketUrl = route('ticket.show', $token);
        $checkinUrl = route('checkin.handle', $token);
        $qrcode = QrCode::format('svg')->size(300)->generate($checkinUrl);

        return view('ticket.show', compact('participant', 'qrcode', 'ticketUrl', 'participantData', 'type'));
    }
}
