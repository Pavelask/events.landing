<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'content',
        'photo',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function eventTestimonials(): HasMany
    {
        return $this->hasMany(EventTestimonial::class)->orderBy('sort_order');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_testimonial')
            ->withPivot(['sort_order', 'is_visible'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        return null;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function (Testimonial $testimonial) {
            if ($testimonial->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
        });
    }
}
