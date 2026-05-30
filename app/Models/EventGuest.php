<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventGuest extends Model
{
    protected $table = 'event_guest';
    protected $fillable = ['event_id', 'guest_id', 'sort_order', 'is_visible', 'is_keynote'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
