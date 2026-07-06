<?php

namespace App\Exports;

use App\Models\AnonParticipant;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Model;

class AnonParticipantExport extends Exporter
{
    protected static ?string $model = AnonParticipant::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('event.title')->label('Мероприятие'),
            ExportColumn::make('status')->label('Статус'),
            ExportColumn::make('created_at')->label('Дата регистрации'),
            ExportColumn::make('checked_in_at')->label('Чек-ин'),
            ExportColumn::make('ticket_sent_at')->label('Билет отправлен'),
            ExportColumn::make('souvenir_given')->label('Сувенир'),
            ExportColumn::make('documentation_given')->label('Документация'),
            ExportColumn::make('clothing_given')->label('Одежда'),
        ];
    }

    public function __invoke(Model $record): array
    {
        return [
            'id' => $record->id,
            'event.title' => $record->event->title ?? '',
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
