<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventFaq extends Model
{
    protected $table = 'event_faq';

    protected $fillable = [
        'event_id',
        'faq_id',
        'sort_order',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function faq(): BelongsTo
    {
        return $this->belongsTo(Faq::class);
    }
}
