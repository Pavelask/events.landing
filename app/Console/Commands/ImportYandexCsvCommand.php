<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportYandexCsvCommand extends Command
{
    protected $signature = 'import:yandex-csv {file}';
    protected $description = 'Импортирует участников из CSV-файла Яндекс.Форм';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (!File::exists($file)) {
            $this->error("Файл {$file} не найден.");
            return self::FAILURE;
        }

        $handle = fopen($file, 'r');
        if (!$handle) {
            $this->error("Не удалось открыть файл {$file}.");
            return self::FAILURE;
        }

        $headers = fgetcsv($handle, 0, ',');
        if (!$headers) {
            $this->error("Файл пуст или не удалось прочитать заголовки.");
            fclose($handle);
            return self::FAILURE;
        }

        $created = 0;
        $skipped = 0;
        $errors = 0;

        $bar = $this->output->createProgressBar(count(file($file)) - 1);
        $bar->start();

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $data = array_combine($headers, $row);

            $eventId = $data['event_id'] ?? null;
            $email = $data['email'] ?? null;
            $name = $data['name'] ?? null;

            if (!$eventId || !$name) {
                $errors++;
                $bar->advance();
                continue;
            }

            $exists = Participant::where('event_id', $eventId)
                ->where('email', $email)
                ->exists();

            if ($exists) {
                $skipped++;
                $bar->advance();
                continue;
            }

            try {
                $participant = Participant::create([
                    'event_id' => $eventId,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $data['phone'] ?? null,
                    'answers' => $data,
                    'source' => 'import',
                ]);

                $participant->generateCheckinToken();
                $created++;
            } catch (\Exception $e) {
                $errors++;
                $this->error("\nОшибка создания участника: {$e->getMessage()}");
            }

            $bar->advance();
        }

        fclose($handle);
        $bar->finish();
        $this->newLine();

        $this->info("Импорт завершен:");
        $this->info("  Создано: {$created}");
        $this->info("  Пропущено (дубликаты): {$skipped}");
        $this->info("  Ошибки: {$errors}");

        return self::SUCCESS;
    }
}
