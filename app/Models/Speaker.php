<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Speaker extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'position',
        'organization',
        'description',
        'photo',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->withPivot(['is_keynote', 'sort_order'])
            ->withTimestamps();
    }

    public function scheduleEvents(): HasMany
    {
        return $this->hasMany(ScheduleEvent::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        return Storage::url('img/Simpleicons_Interface_user-black-close-up-shape.svg.png');
    }
}
