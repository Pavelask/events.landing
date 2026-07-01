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

        $qrPath = storage_path('app/temp_qr_' . $token . '.png');
        QrCode::format('png')->size(300)->generate($checkinUrl, $qrPath);

        $qrBase64 = base64_encode(file_get_contents($qrPath));
        $qrDataUrl = 'data:image/png;base64,' . $qrBase64;

        @unlink($qrPath);

        $pdf = Pdf::loadView('ticket.pdf', compact('participant', 'qrDataUrl'))
            ->setPaper('a4')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download("ticket-{$participant->checkin_token}.pdf");
    }
}
