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
        $page = 1;

        $this->info("Importing answers from form {$formId}...");

        do {
            $answers = $api->getAnswers($formId, [
                'page' => $page,
                'pageSize' => 100,
            ]);

            if (empty($answers)) {
                break;
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

                try {
                    Participant::create([
                        'event_id' => $eventId,
                        'answer_id' => (string) $answerId,
                        'checkin_token' => Str::random(40),
                        'status' => 'registered',
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $this->error("Error creating participant for answer {$answerId}: {$e->getMessage()}");
                    $errors++;
                }
            }

            $page++;
            usleep(100000); // 100ms delay between pages

        } while (count($answers) === 100);

        $this->info("Done!");
        $this->info("Imported: {$imported}");
        $this->info("Skipped (duplicates): {$skipped}");
        $this->info("Errors: {$errors}");

        return self::SUCCESS;
    }
}
