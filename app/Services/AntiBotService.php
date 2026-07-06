<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class AntiBotService
{
    public function verify(?string $honeypot, ?int $formLoadedAt, ?string $mathAnswer): bool
    {
        return $this->checkHoneypot($honeypot)
            && $this->checkTime($formLoadedAt)
            && $this->checkMathAnswer($mathAnswer);
    }

    private function checkHoneypot(?string $value): bool
    {
        return empty($value);
    }

    private function checkTime(?int $formLoadedAt): bool
    {
        if (!$formLoadedAt) {
            return false;
        }

        $elapsed = time() - $formLoadedAt;

        return $elapsed >= 3;
    }

    private function checkMathAnswer(?string $answer): bool
    {
        $correctAnswer = Session::get('math_answer');

        if ($correctAnswer === null) {
            return false;
        }

        Session::forget('math_answer');

        return (int) $answer === (int) $correctAnswer;
    }

    public function generateMathQuestion(): array
    {
        $a = random_int(1, 20);
        $b = random_int(1, 20);
        $answer = $a + $b;

        Session::put('math_answer', $answer);

        return [
            'a' => $a,
            'b' => $b,
        ];
    }
}
