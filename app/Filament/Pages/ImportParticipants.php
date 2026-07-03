<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\Participant;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class ImportParticipants extends Page
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    protected string $view = 'filament.pages.import-participants';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = 'Импорт участников';

    protected static string|\UnitEnum|null $navigationGroup = 'Мероприятия';

    protected static bool $navigationHidden = true;

    protected static ?int $navigationSort = 5;

    public ?array $data = ['event_id' => 1, 'csv_file' => null];
    public int $importedCount = 0;
    public int $skippedCount = 0;
    public bool $showResult = false;

    public function getTitle(): string
    {
        return 'Импорт участников из CSV';
    }

    public function mount(): void
    {
        $this->form->fill($this->data);
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Select::make('event_id')
                    ->label('Мероприятие')
                    ->options(fn () => Event::pluck('title', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                FileUpload::make('csv_file')
                    ->label('CSV файл')
                    ->acceptedFileTypes(['text/csv', 'text/plain'])
                    ->required(),
            ]);
    }

    public function importCsv(): void
    {
        $this->showResult = false;

        $data = $this->form->getState();

        $file = $data['csv_file'] ?? null;
        $eventId = $data['event_id'] ?? 1;

        if (!$file) {
            Notification::make()->danger('Загрузите CSV файл')->send();
            return;
        }

        $path = is_string($file) ? $file : $file->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            Notification::make()->danger('Не удалось открыть файл')->send();
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

            $exists = Participant::where('event_id', $eventId)
                ->where('answer_id', $yandexId)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

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
        }

        fclose($handle);

        $this->importedCount = $imported;
        $this->skippedCount = $skipped;
        $this->showResult = true;

        Notification::make()
            ->title("Импорт завершён")
            ->body("Импортировано: {$imported}, Пропущено: {$skipped}")
            ->success()
            ->send();
    }
}
