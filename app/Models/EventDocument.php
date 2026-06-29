<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class EventDocument extends Model
{
    protected $fillable = ['event_id', 'title', 'file_path', 'file_type', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function (EventDocument $document) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
        });
    }
}
