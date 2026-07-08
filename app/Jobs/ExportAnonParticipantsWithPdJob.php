<?php

namespace App\Jobs;

use App\Models\AnonParticipant;
use App\Services\YandexFormsApi;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ExportAnonParticipantsWithPdJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $filters,
        public int $adminId,
    ) {}

    public function handle(YandexFormsApi $yandexApi): void
    {
        $query = AnonParticipant::with('event.formTemplate');

        if (!empty($this->filters['event_id'])) {
            $query->where('event_id', $this->filters['event_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $participants = $query->get();

        if ($participants->isEmpty()) {
            $this->notifyAdmin('Нет участников для экспорта.');
            return;
        }

        $data = [];
        $allSlugs = collect();
        $errors = [];
        $skippedNoForm = 0;
        $skippedNoAnswer = 0;
        $skippedLocalOnly = 0;

        foreach ($participants as $participant) {
            $formId = $participant->event->formTemplate->yandex_form_id ?? null;
            if (!$formId) {
                $skippedNoForm++;
                $errors[] = "ID #{$participant->id}: нет form_id у мероприятия";
                continue;
            }

            if (str_starts_with($participant->answer_id, 'LOCAL_')) {
                $skippedLocalOnly++;
                $errors[] = "ID #{$participant->id}: локальный ответ ({$participant->answer_id}), данные не в Яндекс Форме";
                continue;
            }

            $answer = $yandexApi->getAnswer($formId, $participant->answer_id);

            if (!$answer) {
                $skippedNoAnswer++;
                $errors[] = "ID #{$participant->id}: API вернул ошибку (answer_id: {$participant->answer_id}, form_id: {$formId})";
                Log::warning('ExportAnonParticipantsWithPdJob: getAnswer failed', [
                    'participant_id' => $participant->id,
                    'answer_id' => $participant->answer_id,
                    'form_id' => $formId,
                ]);
                continue;
            }

            $answerData = $answer['data'] ?? [];
            $answerMap = [];
            foreach ($answerData as $item) {
                $label = $item['label'] ?? $item['id'] ?? '';
                $answerMap[strtolower($label)] = $item['value'] ?? '';
            }

            $row = [
                'ID' => $participant->id,
                'Мероприятие' => $participant->event->title ?? '',
                'Статус' => $participant->status_label,
                'Дата регистрации' => $participant->created_at->format('d.m.Y H:i'),
                'Чек-ин' => $participant->checked_in_at?->format('d.m.Y H:i') ?? '',
                'Имя' => $answerMap['фио участника'] ?? $answerMap['имя'] ?? $answerMap['name'] ?? $answerMap['фио'] ?? '',
                'Email' => $answerMap['почта'] ?? $answerMap['email'] ?? $answerMap['электронная почта'] ?? '',
                'Телефон' => $answerMap['телефон'] ?? $answerMap['phone'] ?? '',
            ];

            $eventQuestions = $participant->event->questions ?? [];
            foreach ($eventQuestions as $index => $question) {
                $slug = $question['slug'] ?? "custom_" . ($index + 1);
                $label = strtolower($question['label'] ?? '');
                $allSlugs->push($slug);
                $row[$slug] = $answerMap[$label] ?? '';
            }

            $data[] = $row;
        }

        if (empty($data)) {
            $errorMsg = "Нет данных для экспорта.";
            if (!empty($errors)) {
                $errorMsg .= " Ошибки: " . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $errorMsg .= "... (всего " . count($errors) . ")";
                }
            }
            $this->notifyAdmin($errorMsg);
            return;
        }

        $allSlugs = $allSlugs->unique()->values();
        $headers = ['ID', 'Мероприятие', 'Статус', 'Дата регистрации', 'Чек-ин', 'Имя', 'Email', 'Телефон'];
        $headers = array_merge($headers, $allSlugs->toArray());

        $finalData = array_map(function ($row) use ($allSlugs) {
            foreach ($allSlugs as $slug) {
                if (!isset($row[$slug])) {
                    $row[$slug] = '';
                }
            }
            return $row;
        }, $data);

        $filename = 'participants_with_pd_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $path = "exports/{$filename}";

        $export = new class($finalData, $headers) implements \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\ShouldAutoSize {
            public function __construct(
                private array $data,
                private array $headers,
            ) {}

            public function collection()
            {
                return collect($this->data);
            }

            public function headings(): array
            {
                return $this->headers;
            }
        };

        Excel::store($export, $path, 'local');

        $body = "Файл {$filename} готов. Экспортировано: " . count($data) . " из " . $participants->count();
        if (!empty($errors)) {
            $body .= ". Пропущено/ошибки (" . count($errors) . "): " . implode('; ', array_slice($errors, 0, 3));
            if (count($errors) > 3) {
                $body .= "...";
            }
        }

        Notification::make()
            ->title('Экспорт с ПД готов')
            ->body($body)
            ->success()
            ->send();
    }

    private function notifyAdmin(string $message): void
    {
        Notification::make()
            ->title('Экспорт с ПД')
            ->body($message)
            ->danger()
            ->send();
    }
}
