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

            $participantData = [
                'name' => $answer['answerer']['fields']['name'] ?? 'Участник',
                'email' => $answer['answerer']['email'] ?? '',
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
