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
        @unlink($qrPath);

        $title = $participant->event->title;
        $dates = $participant->event->start_date->format('d.m') . ' — ' . $participant->event->end_date->format('d.m.Y');
        $venue = $participant->event->venue_name ?? '';
        $name = $participant->name;
        $email = $participant->email ?? '';
        $phone = $participant->phone ?? '';

        $html = '<!DOCTYPE html>
<html><head><meta charset="UTF-8"><style>
body{font-family:DejaVu Sans;margin:0;padding:40px;background:#fff}
.ticket{max-width:600px;margin:0 auto;border:2px solid #ddd;border-radius:10px;overflow:hidden}
.hdr{background:#667eea;color:#fff;padding:30px;text-align:center}
.hdr h1{font-size:20px;margin:0 0 8px;font-weight:700}
.hdr p{font-size:14px;margin:0}
.body{padding:30px}
.info{margin-bottom:14px}
.info label{font-size:11px;color:#666;display:block;margin-bottom:2px}
.info span{font-size:15px;color:#333;font-weight:600}
.qr{text-align:center;padding:20px;background:#f9f9f9;border-radius:8px;margin:20px 0}
.qr img{width:180px;height:180px}
.ftr{padding:14px 30px;background:#f9f9f9;text-align:center;font-size:12px;color:#666}
</style></head><body>
<div class="ticket">
<div class="hdr"><h1>' . htmlspecialchars($title) . '</h1><p>' . $dates . '</p>' . ($venue ? '<p>' . htmlspecialchars($venue) . '</p>' : '') . '</div>
<div class="body">
<div class="info"><label>Участник</label><span>' . htmlspecialchars($name) . '</span></div>
' . ($email ? '<div class="info"><label>Email</label><span>' . htmlspecialchars($email) . '</span></div>' : '') . '
' . ($phone ? '<div class="info"><label>Телефон</label><span>' . htmlspecialchars($phone) . '</span></div>' : '') . '
<div class="qr"><img src="data:image/png;base64,' . $qrBase64 . '"></div>
</div>
<div class="ftr">Предъявите QR-код на входе в мероприятие</div>
</div></body></html>';

        $pdf = Pdf::loadHtml($html)
            ->setPaper('a4')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download("ticket-{$participant->checkin_token}.pdf");
    }
}
