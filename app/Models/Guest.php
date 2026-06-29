<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Guest extends Model
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
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        return Storage::url('img/Simpleicons_Interface_user-black-close-up-shape.svg.png');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function (Guest $guest) {
            if ($guest->photo) {
                Storage::disk('public')->delete($guest->photo);
            }
        });
    }
}
