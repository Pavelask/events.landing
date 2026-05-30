<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestimonialEvent extends Model
{
    protected $table = 'testimonial_event';

    protected $fillable = [
        'testimonial_id',
        'event_id',
        'sort_order',
    ];

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
