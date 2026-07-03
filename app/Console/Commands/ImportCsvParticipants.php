<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportCsvParticipants extends Command
{
    protected $signature = 'import:csv {file} {--event-id=}';
    protected $description = 'Import participants from Yandex Forms CSV export';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $handle = fopen($file, 'r');
        if (!$handle) {
            $this->error("Cannot open file: {$file}");
            return self::FAILURE;
        }

        $headers = fgetcsv($handle, 0, ',');
        $this->info("Columns: " . implode(' | ', $headers));

        $imported = 0;
        $skipped = 0;
        $errors = 0;
        $rowNum = 1;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rowNum++;
            $data = array_combine($headers, $row);

            $yandexId = trim($data['ID'] ?? '');
            $name = trim($data['"Фамилия, Имя, Отчество"'] ?? $data['Фамилия, Имя, Отчество'] ?? '');
            $phone = trim($data['Номер телефона (мобильный для связи в пути и в г. Сочи)'] ?? '');
            $email = trim($data['Адрес электронной почты'] ?? '');
            $eventId = trim($data['event_id'] ?? '') ?: $this->option('event-id');

            if (!$yandexId) {
                $errors++;
                continue;
            }

            if (!$name || $name === '00000' || $name === '0000' || $name === '00') {
                $skipped++;
                continue;
            }

            if (!$eventId) {
                $eventId = 1;
            }

            $exists = Participant::where('event_id', $eventId)
                ->where('answer_id', $yandexId)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            try {
                Participant::create([
                    'event_id' => $eventId,
                    'answer_id' => $yandexId,
                    'name' => $name ?: null,
                    'email' => $email ?: null,
                    'phone' => $phone ?: null,
                    'checkin_token' => Str::random(40),
                    'status' => 'registered',
                ]);
                $imported++;
            } catch (\Exception $e) {
                $this->error("Row {$rowNum}: {$e->getMessage()}");
                $errors++;
            }
        }

        fclose($handle);

        $this->info("Done!");
        $this->info("Imported: {$imported}");
        $this->info("Skipped: {$skipped}");
        $this->info("Errors: {$errors}");

        return self::SUCCESS;
    }
}
