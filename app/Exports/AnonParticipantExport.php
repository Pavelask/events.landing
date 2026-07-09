<?php

namespace App\Exports;

use App\Models\AnonParticipant;
use App\Services\YandexFormsApi;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AnonParticipantExport extends Exporter
{
    protected static ?string $model = AnonParticipant::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('event.title')->label('Мероприятие'),
            ExportColumn::make('answer_id')->label('Answer ID'),
            ExportColumn::make('name')->label('ФИО'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('phone')->label('Телефон'),
            ExportColumn::make('status')->label('Статус'),
            ExportColumn::make('created_at')->label('Дата регистрации'),
            ExportColumn::make('checked_in_at')->label('Чек-ин'),
            ExportColumn::make('ticket_sent_at')->label('Билет отправлен'),
            ExportColumn::make('souvenir_given')->label('Сувенир'),
            ExportColumn::make('documentation_given')->label('Документация'),
            ExportColumn::make('clothing_given')->label('Одежда'),
        ];
    }

    protected static function getCachedAnswerData(AnonParticipant $record): ?array
    {
        $cacheKey = "export_answer_{$record->answer_id}";

        return Cache::remember($cacheKey, 600, function () use ($record) {
            $yandexApi = app(YandexFormsApi::class);
            $formId = $record->event->formTemplate->yandex_form_id ?? null;

            if (!$formId || !$record->answer_id) {
                return null;
            }

            return $yandexApi->getAnswer($formId, $record->answer_id);
        });
    }

    protected static function extractField(array $answerData, array $labels): ?string
    {
        foreach ($answerData['data'] ?? [] as $item) {
            $label = mb_strtolower($item['label'] ?? '');
            if (in_array($label, $labels)) {
                return $item['value'] ?? null;
            }
        }
        return null;
    }

    public function __invoke(Model $record): array
    {
        $answerData = static::getCachedAnswerData($record);

        $name = $answerData ? static::extractField($answerData, ['фио участника', 'фамилия имя отчество', 'имя', 'name']) : null;
        $email = $answerData ? static::extractField($answerData, ['почта', 'email', 'электронная почта']) : null;
        $phone = $answerData ? static::extractField($answerData, ['телефон', 'phone', 'номер телефона']) : null;

        return [
            'id' => $record->id,
            'event.title' => $record->event->title ?? '',
            'answer_id' => $record->answer_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $record->status_label,
            'created_at' => $record->created_at?->format('d.m.Y H:i'),
            'checked_in_at' => $record->checked_in_at?->format('d.m.Y H:i'),
            'ticket_sent_at' => $record->ticket_sent_at ? 'Да' : 'Нет',
            'souvenir_given' => $record->souvenir_given ? 'Да' : 'Нет',
            'documentation_given' => $record->documentation_given ? 'Да' : 'Нет',
            'clothing_given' => $record->clothing_given ? 'Да' : 'Нет',
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Экспорт завершён. ';
        $body .= $export->successful_rows . ' записей экспортировано.';
        return $body;
    }
}
