<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ScheduleEvent extends Model
{
    protected $fillable = [
        'event_day_id',
        'speaker_id',
        'start_time',
        'end_time',
        'title',
        'description',
        'icon',
        'icon_image',
        'color',
        'location',
        'is_break',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_break' => 'boolean',
        ];
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(EventDay::class, 'event_day_id');
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function (ScheduleEvent $event) {
            if ($event->icon_image) {
                Storage::disk('public')->delete($event->icon_image);
            }
        });
    }
}
