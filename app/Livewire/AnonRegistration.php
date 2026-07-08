<?php

namespace App\Livewire;

use App\Models\AnonParticipant;
use App\Models\Event;
use App\Services\YandexFormsApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class AnonRegistration extends Component
{
    public ?Event $event = null;

    public array $formData = [];

    public array $questions = [];

    public bool $submitted = false;

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public array $fieldErrors = [];

    public function mount(?Event $event = null): void
    {
        if (!$event || $event->registration_type !== 'yandex_api') {
            abort(404);
        }

        $this->event = $event->load('formTemplate');
        $this->questions = $this->event->formTemplate->questions ?? [];
    }

    private function validateFields(): bool
    {
        $this->fieldErrors = [];

        if (empty($this->formData['name'] ?? null)) {
            $this->fieldErrors['formData.name'] = 'Поле «ФИО» обязательно для заполнения';
        }

        $email = $this->formData['email'] ?? '';
        if ($email !== '') {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->fieldErrors['formData.email'] = 'Введите корректный email адрес';
            }
        } else {
            $this->fieldErrors['formData.email'] = 'Поле «Email» обязательно для заполнения';
        }

        $phone = $this->formData['phone'] ?? '';
        if ($phone) {
            $digits = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($digits) < 11) {
                $this->fieldErrors['formData.phone'] = 'Введите полный номер телефона';
            }
        }

        foreach ($this->questions as $question) {
            $value = $this->formData[$question['slug']] ?? null;

            if ($question['required'] && empty($value)) {
                $this->fieldErrors['formData.' . $question['slug']] = "Поле «{$question['label']}» обязательно для заполнения";
                continue;
            }

            if (!empty($value)) {
                if ($question['type'] === 'date') {
                    if (!preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $value)) {
                        $this->fieldErrors['formData.' . $question['slug']] = "Формат даты: ДД.ММ.ГГГГ";
                        continue;
                    }
                    $parts = explode('.', $value);
                    if (!checkdate((int)$parts[1], (int)$parts[0], (int)$parts[2])) {
                        $this->fieldErrors['formData.' . $question['slug']] = "Некорректная дата";
                    }
                }

                if ($question['type'] === 'select' && !empty($question['options']) && !in_array($value, $question['options'])) {
                    $this->fieldErrors['formData.' . $question['slug']] = "Выберите значение из списка";
                }
            }
        }

        return empty($this->fieldErrors);
    }

    public function submit(YandexFormsApi $yandexApi): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->fieldErrors = [];

        if (!$this->validateFields()) {
            return;
        }

        if ($this->event->registration_deadline && $this->event->registration_deadline->isPast()) {
            $this->errorMessage = 'Регистрация закрыта.';
            return;
        }

        if ($this->event->capacity) {
            $totalCount = AnonParticipant::where('event_id', $this->event->id)
                ->where('status', '!=', 'cancelled')
                ->count();

            if ($totalCount >= $this->event->capacity) {
                $this->errorMessage = 'Места закончились.';
                return;
            }
        }

        $formId = $this->event->formTemplate->yandex_form_id ?? null;
        if (!$formId) {
            $this->errorMessage = 'Форма регистрации не настроена.';
            return;
        }

        $phone = $this->formData['phone'] ?? '';
        if ($phone) {
            $digits = preg_replace('/\D/', '', $phone);
            if (str_starts_with($digits, '8')) {
                $digits = '7' . substr($digits, 1);
            }
            if (!str_starts_with($digits, '7')) {
                $digits = '7' . $digits;
            }
            $phone = '+7' . substr($digits, 1, 10);
        }

        $payload = [
            'event_id' => (string) $this->event->id,
            'name' => $this->formData['name'],
            'email' => $this->formData['email'],
            'phone' => $phone,
        ];

        foreach ($this->questions as $index => $question) {
            $slot = 'custom_' . ($index + 1);
            $value = $this->formData[$question['slug']] ?? '';
            if (is_array($value)) {
                $value = implode(', ', $value);
            } elseif (is_bool($value)) {
                $value = $value ? 'Да' : 'Нет';
            }
            $payload[$slot] = $value;
        }

        $response = $yandexApi->createAnswer($formId, $payload);

        if ($response) {
            $answerId = $response['answer_id'] ?? $response['id'] ?? null;
        } else {
            $answerId = 'LOCAL_' . time() . '_' . Str::random(10);
            Log::warning('AnonRegistration: API failed, saving locally', [
                'form_id' => $formId,
                'payload' => $payload,
            ]);
        }

        $participant = AnonParticipant::create([
            'event_id' => $this->event->id,
            'answer_id' => $answerId,
            'checkin_token' => Str::random(40),
            'status' => 'registered',
            'local_data' => [
                'yandex_name' => $this->formData['name'] ?? '',
                'yandex_email' => $this->formData['email'] ?? '',
                'yandex_phone' => $this->formData['phone'] ?? '',
                ...collect($this->questions)->mapWithKeys(fn ($q, $i) => [
                    'custom_' . $q['slug'] => $this->formData[$q['slug']] ?? '',
                ])->toArray(),
            ],
        ]);

        $email = $this->formData['email'] ?? '';
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            \Illuminate\Support\Facades\Mail::to($email)
                ->send(new \App\Mail\RegistrationConfirmationMail($participant, $this->event->title));
        }

        $this->submitted = true;
        $this->successMessage = 'Вы успешно зарегистрировались!';
    }

    public function render()
    {
        return view('livewire.anon-registration')->layout('components.layouts.app');
    }
}
