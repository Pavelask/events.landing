<?php

namespace App\Services;

use App\Models\AnonParticipant;
use App\Models\Participant;

class CheckinService
{
    public function __construct(private readonly YandexFormsApi $yandexApi) {}

    /**
     * @return array{model: Participant|AnonParticipant, type: string, name: string, email: string}|null
     */
    public function findByToken(string $token): ?array
    {
        $participant = Participant::with('event')
            ->where('checkin_token', $token)
            ->first();

        if ($participant) {
            return [
                'model' => $participant,
                'type' => 'classic',
                'name' => (string) $participant->name,
                'email' => (string) $participant->email,
            ];
        }

        $anonParticipant = AnonParticipant::with('event')
            ->where('checkin_token', $token)
            ->first();

        if (! $anonParticipant) {
            return null;
        }

        [$name, $email] = $this->resolveAnonIdentity($anonParticipant);

        return [
            'model' => $anonParticipant,
            'type' => 'anon',
            'name' => $name,
            'email' => $email,
        ];
    }

    /**
     * Idempotent check-in: only stamps the first time.
     */
    public function checkIn(array $resolved): bool
    {
        $model = $resolved['model'];
        $alreadyCheckedIn = $model->checked_in_at !== null;

        if (! $alreadyCheckedIn) {
            $model->update([
                'checked_in_at' => now(),
                'status' => 'arrived',
            ]);
        }

        return $alreadyCheckedIn;
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function resolveAnonIdentity(AnonParticipant $anonParticipant): array
    {
        $formId = $anonParticipant->event?->formTemplate?->yandex_form_id;
        $answer = $formId && $anonParticipant->answer_id
            ? $this->yandexApi->getAnswer($formId, $anonParticipant->answer_id)
            : [];
        $answerData = $answer['data'] ?? [];

        $name = 'Участник';
        $email = '';

        foreach ($answerData as $item) {
            $label = mb_strtolower($item['label'] ?? '');
            if (in_array($label, ['фио участника', 'имя', 'name', 'фио'], true)) {
                $name = $item['value'] ?? $name;
            }
            if (in_array($label, ['почта', 'email', 'электронная почта'], true)) {
                $email = $item['value'] ?? $email;
            }
        }

        return [$name, $email];
    }
}
