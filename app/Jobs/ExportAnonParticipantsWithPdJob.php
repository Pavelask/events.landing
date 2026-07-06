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
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnonParticipantWithPdExport;

class ExportAnonParticipantsWithPdJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $filters,
        public int $adminId,
    ) {}

    public function handle(YandexFormsApi $yandexApi): void
    {
        $query = AnonParticipant::with('event');

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

        foreach ($participants as $participant) {
            $formId = $participant->event->formTemplate->yandex_form_id ?? null;
            if (!$formId) {
                continue;
            }

            $answer = $yandexApi->getAnswer($formId, $participant->answer_id);

            if (!$answer) {
                continue;
            }

            $row = [
                'ID' => $participant->id,
                'Мероприятие' => $participant->event->title ?? '',
                'Статус' => $participant->status_label,
                'Дата регистрации' => $participant->created_at->format('d.m.Y H:i'),
                'Чек-ин' => $participant->checked_in_at?->format('d.m.Y H:i') ?? '',
                'Имя' => $answer['answerer']['fields']['name'] ?? '',
                'Email' => $answer['answerer']['email'] ?? '',
                'Телефон' => $answer['answerer']['fields']['phone'] ?? '',
            ];

            $eventQuestions = $participant->event->questions ?? [];
            foreach ($eventQuestions as $index => $question) {
                $slug = $question['slug'] ?? "custom_" . ($index + 1);
                $allSlugs->push($slug);
                $row[$slug] = $answer['answerer']['fields']["custom_" . ($index + 1)] ?? '';
            }

            $data[] = $row;
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

        Notification::make()
            ->title('Экспорт с ПД готов')
            ->body("Файл {$filename} готов к скачиванию. Ссылка действительна 1 час.")
            ->success()
            ->send();
    }

    private function notifyAdmin(string $message): void
    {
        Notification::make()
            ->title('Экспорт с ПД')
            ->body($message)
            ->warning()
            ->send();
    }
}
