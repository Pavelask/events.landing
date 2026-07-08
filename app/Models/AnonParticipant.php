<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AnonParticipant extends Model
{
    protected $fillable = [
        'event_id',
        'answer_id',
        'checkin_token',
        'status',
        'checked_in_at',
        'ticket_sent_at',
        'souvenir_given',
        'documentation_given',
        'clothing_given',
        'local_data',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'ticket_sent_at' => 'datetime',
            'souvenir_given' => 'boolean',
            'documentation_given' => 'boolean',
            'clothing_given' => 'boolean',
            'local_data' => 'array',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function generateCheckinToken(): string
    {
        $this->checkin_token = Str::random(40);
        $this->save();

        return $this->checkin_token;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'registered' => 'Зарегистрирован',
            'arrived' => 'Прибыл',
            'cancelled' => 'Отменён',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'registered' => 'gray',
            'arrived' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
