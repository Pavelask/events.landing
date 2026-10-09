<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckinRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Services\CheckinService;
use Illuminate\Http\JsonResponse;

class CheckinController extends Controller
{
    public function byToken(CheckinRequest $request, CheckinService $checkin): TicketResource|JsonResponse
    {
        $resolved = $checkin->findByToken($request->validated('token'));

        if (! $resolved) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $alreadyCheckedIn = $checkin->checkIn($resolved);

        return new TicketResource(
            $resolved['model']->fresh('event'),
            $resolved['name'],
            $resolved['email'],
            $resolved['type'],
            $alreadyCheckedIn,
        );
    }
}
