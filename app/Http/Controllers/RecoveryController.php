<?php

namespace App\Http\Controllers;

use App\Mail\TicketMail;
use App\Mail\VerificationCodeMail;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class RecoveryController extends Controller
{
    public function showForm()
    {
        return view('recovery.form');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $ip = $request->ip();
        $cacheKey = "recovery_request_{$ip}";

        if (Cache::has($cacheKey)) {
            $attempts = Cache::get($cacheKey);
            if ($attempts >= 3) {
                return back()->withErrors(['email' => 'Слишком много запросов. Попробуйте позже.']);
            }
            Cache::put($cacheKey, $attempts + 1, 600);
        } else {
            Cache::put($cacheKey, 1, 600);
        }

        $participant = Participant::where('email', $request->email)->first();

        if (!$participant) {
            return back()->with('message', 'Если такой email существует, код отправлен.');
        }

        $participant->generateVerificationCode();

        Mail::to($participant->email)->send(new VerificationCodeMail($participant));

        session(['recovery_email' => $participant->email]);

        return redirect()->route('recovery.code.form');
    }

    public function showCodeForm()
    {
        $email = session('recovery_email');

        if (!$email) {
            return redirect()->route('recovery.form');
        }

        return view('recovery.code', compact('email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $ip = $request->ip();
        $cacheKey = "recovery_attempt_{$ip}";

        if (Cache::has($cacheKey)) {
            $attempts = Cache::get($cacheKey);
            if ($attempts >= 5) {
                return back()->withErrors(['code' => 'Слишком много попыток. Попробуйте позже.']);
            }
            Cache::put($cacheKey, $attempts + 1, 600);
        } else {
            Cache::put($cacheKey, 1, 600);
        }

        $email = session('recovery_email');

        if (!$email) {
            return redirect()->route('recovery.form');
        }

        $participant = Participant::where('email', $email)
            ->where('verification_code', $request->code)
            ->where('verification_code_sent_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$participant) {
            return back()->withErrors(['code' => 'Неверный или просроченный код.']);
        }

        $participant->update([
            'verification_code' => null,
            'verification_code_sent_at' => null,
        ]);

        $ticketUrl = route('ticket.show', $participant->checkin_token);
        Mail::to($participant->email)->send(new TicketMail($participant, $ticketUrl));

        Cache::forget("recovery_attempt_{$ip}");
        session()->forget('recovery_email');

        return redirect()->route('ticket.show', $participant->checkin_token);
    }
}
