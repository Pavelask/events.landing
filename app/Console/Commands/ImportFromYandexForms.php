<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Participant;
use App\Services\YandexFormsApi;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportFromYandexForms extends Command
{
    protected $signature = 'import:yandex-forms {formId} {--event-id=}';
    protected $description = 'Import answers from Yandex Forms into participants table';

    public function handle(): int
    {
        $formId = $this->argument('formId');
        $eventId = $this->option('event-id');

        if (!$eventId) {
            $event = Event::where('yandex_form_id', $formId)->first();
            if (!$event) {
                $this->error("Event not found for form_id: {$formId}");
                return self::FAILURE;
            }
            $eventId = $event->id;
            $this->info("Found event: {$event->title} (id={$eventId})");
        }

        $event = Event::find($eventId);
        if (!$event) {
            $this->error("Event with id={$eventId} not found");
            return self::FAILURE;
        }

        $api = app(YandexFormsApi::class);
        $imported = 0;
        $skipped = 0;
        $errors = 0;

        $this->info("Importing answers from form {$formId}...");

        $answers = $api->getAnswers($formId, ['format' => 'raw', 'page_size' => 100]);

        if (empty($answers)) {
            $this->error("No answers found or API unreachable");
            return self::FAILURE;
        }

        foreach ($answers as $answer) {
            $answerId = $answer['id'] ?? null;

            if (!$answerId) {
                $errors++;
                continue;
            }

            $exists = Participant::where('event_id', $eventId)
                ->where('answer_id', (string) $answerId)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $name = null;
            $email = null;
            $phone = null;

            foreach ($answer['data'] ?? [] as $key => $item) {
                $value = $item['value'] ?? null;
                if (is_array($value)) {
                    $value = $value['text'] ?? $value['key'] ?? null;
                }
                if (!$value || !is_string($value)) continue;

                if (str_contains($key, 'email') || filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $email = $value;
                } elseif (str_contains($key, 'phone') || preg_match('/^\+?\d[\d\s\-\(\)]+$/', $value)) {
                    $phone = $value;
                } elseif (!$name && strlen($value) > 2) {
                    $name = $value;
                }
            }

            try {
                Participant::create([
                    'event_id' => $eventId,
                    'answer_id' => (string) $answerId,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'checkin_token' => Str::random(40),
                    'status' => 'registered',
                ]);
                $imported++;
            } catch (\Exception $e) {
                $this->error("Error: {$e->getMessage()}");
                $errors++;
            }
        }

        $this->info("Done!");
        $this->info("Imported: {$imported}");
        $this->info("Skipped (duplicates): {$skipped}");
        $this->info("Errors: {$errors}");

        return self::SUCCESS;
    }
}
