<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function handle(string $token)
    {
        $participant = Participant::with('event')
            ->where('checkin_token', $token)
            ->firstOrFail();

        $alreadyCheckedIn = $participant->checked_in_at !== null;

        if (!$alreadyCheckedIn) {
            $participant->update([
                'checked_in_at' => now(),
                'status' => 'arrived',
            ]);
        }

        return view('checkin.result', compact('participant', 'alreadyCheckedIn'));
    }
}
