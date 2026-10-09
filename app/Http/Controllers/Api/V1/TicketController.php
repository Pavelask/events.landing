<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TicketResource;
use App\Services\CheckinService;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function show(string $token, CheckinService $checkin): TicketResource|JsonResponse
    {
        $resolved = $checkin->findByToken($token);

        if (! $resolved) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return new TicketResource(
            $resolved['model'],
            $resolved['name'],
            $resolved['email'],
            $resolved['type'],
        );
    }

    public function qr(string $token, QrCodeService $qrCode, CheckinService $checkin): Response
    {
        abort_unless($checkin->findByToken($token), 404);

        $png = $qrCode->png(route('checkin.handle', $token));

        return response($png)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
