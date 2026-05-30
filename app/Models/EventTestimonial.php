<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTestimonial extends Model
{
    protected $table = 'event_testimonial';

    protected $fillable = ['event_id', 'testimonial_id', 'sort_order', 'is_visible'];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }
}
