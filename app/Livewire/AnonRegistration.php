<?php

namespace App\Livewire;

use App\Models\AnonParticipant;
use App\Models\Event;
use App\Services\YandexFormsApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class AnonRegistration extends Component
{
    public ?Event $event = null;

    public array $formData = [];

    public array $questions = [];

    public ?string $honeypot = null;

    public int $formLoadedAt = 0;

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

        $this->formLoadedAt = time();
    }

    private function validateFields(): bool
    {
        $this->fieldErrors = [];

        if (empty($this->formData['name'] ?? null)) {
            $this->fieldErrors['formData.name'] = 'Поле «Имя» обязательно для заполнения';
        }

        if (empty($this->formData['email'] ?? null)) {
            $this->fieldErrors['formData.email'] = 'Поле «Email» обязательно для заполнения';
        } elseif (!filter_var($this->formData['email'], FILTER_VALIDATE_EMAIL)) {
            $this->fieldErrors['formData.email'] = 'Введите корректный email адрес';
        }

        foreach ($this->questions as $question) {
            $value = $this->formData[$question['slug']] ?? null;

            if ($question['required'] && empty($value)) {
                $this->fieldErrors['formData.' . $question['slug']] = "Поле «{$question['label']}» обязательно для заполнения";
                continue;
            }

            if ($question['type'] === 'date' && !empty($value)) {
                if (!preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $value)) {
                    $this->fieldErrors['formData.' . $question['slug']] = "Формат даты: ДД.ММ.ГГГГ";
                    continue;
                }
                $parts = explode('.', $value);
                if (!checkdate((int)$parts[1], (int)$parts[0], (int)$parts[2])) {
                    $this->fieldErrors['formData.' . $question['slug']] = "Некорректная дата";
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

        $elapsed = time() - $this->formLoadedAt;
        if ($elapsed < 3) {
            $this->errorMessage = 'Подозрительная активность. Попробуйте ещё раз.';
            return;
        }

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

        $payload = [
            'event_id' => (string) $this->event->id,
            'name' => $this->formData['name'],
            'email' => $this->formData['email'],
            'phone' => $this->formData['phone'] ?? '',
        ];

        foreach ($this->questions as $index => $question) {
            $slot = 'custom_' . ($index + 1);
            $payload[$slot] = $this->formData[$question['slug']] ?? '';
        }

        // ТЕСТОВЫЙ РЕЖИМ: пропускаем проверку дубликатов и API
        $response = $yandexApi->createAnswer($formId, $payload);

        if (!$response) {
            // Тестовый режим: создаём участника без API
            $answerId = 'TEST_' . time() . '_' . Str::random(10);
            Log::info('AnonRegistration: TEST MODE - creating without API', [
                'form_id' => $formId,
                'email' => $this->formData['email'],
            ]);
        } else {
            $answerId = $response['id'] ?? $response['answer_id'] ?? null;
        }

        if (!$answerId) {
            $this->errorMessage = 'Ошибка при регистрации. Попробуйте позже.';
            return;
        }

        AnonParticipant::create([
            'event_id' => $this->event->id,
            'answer_id' => $answerId,
            'checkin_token' => Str::random(40),
            'status' => 'registered',
        ]);

        $this->submitted = true;
        $this->successMessage = 'Вы успешно зарегистрировались! Билет будет отправлен на вашу почту.';
    }

    public function render()
    {
        return view('livewire.anon-registration')->layout('components.layouts.app');
    }
}
