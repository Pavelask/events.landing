<?php

namespace App\Exports;

use App\Models\Participant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantExport implements FromCollection, WithHeadings, WithMapping
{
    protected ?int $eventId;

    public function __construct(?int $eventId = null)
    {
        $this->eventId = $eventId;
    }

    public function collection(): Collection
    {
        $query = Participant::with('event');

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Мероприятие',
            'Имя',
            'Email',
            'Телефон',
            'Статус',
            'Источник',
            'Дата регистрации',
            'Время чек-ина',
        ];
    }

    public function map($participant): array
    {
        return [
            $participant->id,
            $participant->event->title,
            $participant->name,
            $participant->email,
            $participant->phone,
            $participant->status_label,
            $participant->source,
            $participant->created_at->format('d.m.Y H:i'),
            $participant->checked_in_at?->format('d.m.Y H:i'),
        ];
    }
}
