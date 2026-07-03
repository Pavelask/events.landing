<?php

namespace App\Filament\Pages;

use App\Models\Participant;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImportParticipants extends Page
{
    protected string $view = 'filament.pages.import-participants';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = 'Импорт участников';

    protected static string|\UnitEnum|null $navigationGroup = 'Мероприятия';

    protected static ?int $navigationSort = 5;

    public ?int $eventId = 1;
    public $csvFile = null;
    public array $preview = [];
    public int $importedCount = 0;
    public int $skippedCount = 0;

    public function getTitle(): string
    {
        return 'Импорт участников из CSV';
    }

    public function importCsv(): void
    {
        if (!$this->csvFile) {
            Notification::make()->error('Загрузите CSV файл')->send();
            return;
        }

        $file = $this->csvFile;
        if (!$file instanceof UploadedFile) {
            Notification::make()->error('Неверный формат файла')->send();
            return;
        }

        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            Notification::make()->error('Не удалось открыть файл')->send();
            return;
        }

        $headers = fgetcsv($handle, 0, ',');
        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $data = array_combine($headers, $row);

            $yandexId = trim($data['ID'] ?? '');
            $name = trim($data['"Фамилия, Имя, Отчество"'] ?? $data['Фамилия, Имя, Отчество'] ?? '');
            $phone = trim($data['Номер телефона (мобильный для связи в пути и в г. Сочи)'] ?? '');
            $email = trim($data['Адрес электронной почты'] ?? '');

            if (!$yandexId || !$name || in_array($name, ['00000', '0000', '00'])) {
                $skipped++;
                continue;
            }

            $exists = Participant::where('event_id', $this->eventId)
                ->where('answer_id', $yandexId)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Participant::create([
                'event_id' => $this->eventId,
                'answer_id' => $yandexId,
                'name' => $name ?: null,
                'email' => $email ?: null,
                'phone' => $phone ?: null,
                'checkin_token' => Str::random(40),
                'status' => 'registered',
            ]);

            $imported++;
        }

        fclose($handle);

        $this->importedCount = $imported;
        $this->skippedCount = $skipped;

        Notification::make()
            ->title("Импорт завершён")
            ->body("Импортировано: {$imported}, Пропущено: {$skipped}")
            ->success()
            ->send();
    }
}
