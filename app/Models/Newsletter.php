<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Newsletter extends Model
{
    protected $fillable = [
        'event_id',
        'email_template_id',
        'name',
        'filters',
        'total_count',
        'sent_count',
        'failed_count',
        'status',
        'created_by',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'total_count' => 'integer',
            'sent_count' => 'integer',
            'failed_count' => 'integer',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Список получателей (Participant) с учётом фильтров.
     * Анонимные участники не хранят email в БД, поэтому в рассылку не попадают.
     */
    public function recipients(): Collection
    {
        $filters = $this->filters ?? ['statuses' => ['registered', 'verified']];
        $statuses = $filters['statuses'] ?? ['registered', 'verified'];
        $excludeTicketNotSent = $filters['only_without_ticket'] ?? false;

        return Participant::query()
            ->where('event_id', $this->event_id)
            ->when($statuses, fn ($q) => $q->whereIn('status', $statuses))
            ->when($excludeTicketNotSent, fn ($q) => $q->whereNull('ticket_sent_at'))
            ->whereNotNull('email')
            ->get();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Черновик',
            'pending' => 'В очереди',
            'running' => 'Отправляется',
            'completed' => 'Завершена',
            'failed' => 'Ошибка',
            'cancelled' => 'Отменена',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'pending' => 'warning',
            'running' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
