<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'answers',
        'answer_id',
        'checkin_token',
        'checked_in_at',
        'status',
        'ticket_sent_at',
        'verification_code',
        'verification_code_sent_at',
        'email_verified_at',
        'source',
        'utm_tags',
    ];

    protected $attributes = [
        'status' => 'registered',
    ];

    protected $casts = [
        'answers' => 'array',
        'utm_tags' => 'array',
        'checked_in_at' => 'datetime',
        'ticket_sent_at' => 'datetime',
        'verification_code_sent_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

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

    public function generateVerificationCode(): string
    {
        $this->verification_code = (string) random_int(100000, 999999);
        $this->verification_code_sent_at = now();
        $this->save();

        return $this->verification_code;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'registered' => 'Зарегистрирован',
            'verified' => 'Подтверждён',
            'arrived' => 'Прибыл',
            'cancelled' => 'Отменён',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'registered' => 'gray',
            'verified' => 'blue',
            'arrived' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }
}
