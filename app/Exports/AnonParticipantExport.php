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

    protected static array $questionLabels = [];

    public static function getColumns(): array
    {
        $columns = [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('event.title')->label('Мероприятие'),
            ExportColumn::make('answer_id')->label('Answer ID'),
            ExportColumn::make('name')->label('ФИО'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('phone')->label('Телефон'),
        ];

        $questions = static::getAllQuestionLabels();
        foreach ($questions as $slug => $label) {
            $columns[] = ExportColumn::make("question_{$slug}")->label($label);
        }

        $columns[] = ExportColumn::make('status')->label('Статус');
        $columns[] = ExportColumn::make('created_at')->label('Дата регистрации');
        $columns[] = ExportColumn::make('checked_in_at')->label('Чек-ин');
        $columns[] = ExportColumn::make('ticket_sent_at')->label('Билет отправлен');
        $columns[] = ExportColumn::make('souvenir_given')->label('Сувенир');
        $columns[] = ExportColumn::make('documentation_given')->label('Документация');
        $columns[] = ExportColumn::make('clothing_given')->label('Одежда');

        return $columns;
    }

    protected static function getAllQuestionLabels(): array
    {
        if (!empty(static::$questionLabels)) {
            return static::$questionLabels;
        }

        $templates = \App\Models\FormTemplate::whereNotNull('questions')->get();
        $questions = [];

        foreach ($templates as $template) {
            foreach ($template->questions ?? [] as $question) {
                $slug = $question['slug'] ?? null;
                $label = $question['label'] ?? null;
                if ($slug && $label && !isset($questions[$slug])) {
                    $questions[$slug] = $label;
                }
            }
        }

        static::$questionLabels = $questions;
        return $questions;
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

    protected static function extractAllFields(array $answerData): array
    {
        $fields = [];
        foreach ($answerData['data'] ?? [] as $item) {
            $label = $item['label'] ?? '';
            $value = $item['value'] ?? '';
            if ($label && $value) {
                $fields[mb_strtolower($label)] = $value;
            }
        }
        return $fields;
    }

    public function __invoke(Model $record): array
    {
        $answerData = static::getCachedAnswerData($record);

        $name = $answerData ? static::extractField($answerData, ['фио участника', 'фамилия имя отчество', 'имя', 'name']) : null;
        $email = $answerData ? static::extractField($answerData, ['почта', 'email', 'электронная почта']) : null;
        $phone = $answerData ? static::extractField($answerData, ['телефон', 'phone', 'номер телефона']) : null;

        $allFields = $answerData ? static::extractAllFields($answerData) : [];

        $result = [
            'id' => $record->id,
            'event.title' => $record->event->title ?? '',
            'answer_id' => $record->answer_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ];

        $questions = static::getAllQuestionLabels();
        foreach ($questions as $slug => $label) {
            $lowerLabel = mb_strtolower($label);
            $result["question_{$slug}"] = $allFields[$lowerLabel] ?? null;
        }

        $result['status'] = $record->status_label;
        $result['created_at'] = $record->created_at?->format('d.m.Y H:i');
        $result['checked_in_at'] = $record->checked_in_at?->format('d.m.Y H:i');
        $result['ticket_sent_at'] = $record->ticket_sent_at ? 'Да' : 'Нет';
        $result['souvenir_given'] = $record->souvenir_given ? 'Да' : 'Нет';
        $result['documentation_given'] = $record->documentation_given ? 'Да' : 'Нет';
        $result['clothing_given'] = $record->clothing_given ? 'Да' : 'Нет';

        return $result;
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Экспорт завершён. ';
        $body .= $export->successful_rows . ' записей экспортировано.';
        return $body;
    }
}
