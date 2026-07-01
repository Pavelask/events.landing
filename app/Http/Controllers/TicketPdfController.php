<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketPdfController extends Controller
{
    public function download(string $token)
    {
        $participant = Participant::with('event')
            ->where('checkin_token', $token)
            ->firstOrFail();

        $checkinUrl = route('checkin.handle', $token);
        $qrcode = QrCode::format('svg')->size(200)->generate($checkinUrl);

        $pdf = Pdf::loadView('ticket.pdf', compact('participant', 'qrcode'))
            ->setPaper('a4');

        return $pdf->download("ticket-{$participant->checkin_token}.pdf");
    }
}
