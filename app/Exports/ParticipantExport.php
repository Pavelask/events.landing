<?php

namespace App\Exports;

use App\Models\Participant;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Model;

class ParticipantExport extends Exporter
{
    protected static ?string $model = Participant::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('event.title')->label('Мероприятие'),
            ExportColumn::make('name')->label('Имя'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('phone')->label('Телефон'),
            ExportColumn::make('status')->label('Статус'),
            ExportColumn::make('source')->label('Источник'),
            ExportColumn::make('created_at')->label('Дата регистрации')->dateTime('d.m.Y H:i'),
            ExportColumn::make('checked_in_at')->label('Чек-ин')->dateTime('d.m.Y H:i'),
        ];
    }

    public function __invoke(Model $record): array
    {
        return [
            'id' => $record->id,
            'event.title' => $record->event->title ?? '',
            'name' => $record->name,
            'email' => $record->email,
            'phone' => $record->phone,
            'status' => $record->status,
            'source' => $record->source,
            'created_at' => $record->created_at,
            'checked_in_at' => $record->checked_in_at,
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Экспорт завершён. ';
        $body .= $export->successful_rows . ' записей экспортировано.';
        return $body;
    }
}
