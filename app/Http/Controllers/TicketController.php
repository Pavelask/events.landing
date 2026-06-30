<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function show(string $token)
    {
        $participant = Participant::with('event')
            ->where('checkin_token', $token)
            ->firstOrFail();

        $ticketUrl = route('ticket.show', $token);
        $qrcode = QrCode::format('svg')->size(300)->generate($ticketUrl);

        return view('ticket.show', compact('participant', 'qrcode', 'ticketUrl'));
    }
}
