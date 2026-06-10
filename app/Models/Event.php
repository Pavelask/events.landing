<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $fillable = ['title','slug','description','start_date','end_date','daily_start_time','daily_end_time','timezone','status','venue_name','venue_address','venue_lat','venue_lng','venue_how_to_get','show_privacy_section','privacy_policy','personal_data_consent','poster_image','logo','video_url','gallery','social_links','contact_email','contact_phone','registration_type','registration_url','yandex_form_url','is_registration_open','media_image','media_description','is_media_visible','created_by'];

    protected function casts(): array
    {
        return ['start_date'=>'date','end_date'=>'date','daily_start_time'=>'string','daily_end_time'=>'string','venue_lat'=>'decimal:7','venue_lng'=>'decimal:7','gallery'=>'array','social_links'=>'array','is_registration_open'=>'boolean','is_media_visible'=>'boolean','show_privacy_section'=>'boolean'];
    }

    public function getSlugOptions(): SlugOptions { return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug'); }
    public function heroSlides(): HasMany { return $this->hasMany(HeroSlide::class)->orderBy('sort_order'); }
    public function eventFaqs(): HasMany { return $this->hasMany(EventFaq::class)->orderBy('sort_order'); }
    public function documents(): HasMany { return $this->hasMany(EventDocument::class)->orderBy('sort_order'); }
    public function faqs(): BelongsToMany { return $this->belongsToMany(Faq::class, 'event_faq')->withPivot('sort_order')->withTimestamps()->orderByPivot('sort_order'); }
    public function days(): HasMany { return $this->hasMany(EventDay::class)->orderBy('date')->orderBy('sort_order'); }
    public function eventSpeakers(): HasMany { return $this->hasMany(EventSpeaker::class)->orderBy('sort_order'); }
    public function speakers(): BelongsToMany { return $this->belongsToMany(Speaker::class)->withPivot(['is_keynote','sort_order'])->withTimestamps()->orderByPivot('sort_order'); }
    public function keynoteSpeakers(): BelongsToMany { return $this->speakers()->wherePivot('is_keynote', true); }
    public function eventGuests(): HasMany { return $this->hasMany(EventGuest::class)->orderBy('sort_order'); }
    public function guests(): BelongsToMany { return $this->belongsToMany(Guest::class)->withPivot(['is_visible','sort_order'])->withTimestamps()->orderByPivot('sort_order'); }
    public function eventTestimonials(): HasMany { return $this->hasMany(EventTestimonial::class)->orderBy('sort_order'); }
    public function testimonials(): BelongsToMany { return $this->belongsToMany(Testimonial::class, 'event_testimonial')->withPivot(['sort_order','is_visible'])->withTimestamps()->orderByPivot('sort_order'); }
    public function scheduleEvents(): HasManyThrough { return $this->hasManyThrough(ScheduleEvent::class, EventDay::class); }

    public function getIsActiveAttribute(): bool
    {
        $today = Carbon::now($this->timezone)->startOfDay();
        return $this->status === 'published' && $this->start_date->lte($today) && $this->end_date->gte($today);
    }
    public function getIsUpcomingAttribute(): bool { return $this->status === 'published' && $this->start_date->gt(Carbon::now($this->timezone)->startOfDay()); }
    public function getIsCompletedAttribute(): bool { return $this->status === 'published' && $this->end_date->lt(Carbon::now($this->timezone)->startOfDay()); }
    public function getIsRecentlyCompletedAttribute(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }
        $today = Carbon::now($this->timezone)->startOfDay();
        $threeMonthsAgo = $today->copy()->subMonths(3)->startOfDay();
        return $this->end_date->lt($today) && $this->end_date->gte($threeMonthsAgo);
    }
    public function getIsRegistrationAvailableAttribute(): bool
    {
        return $this->is_registration_open && !$this->is_recently_completed;
    }
    public function getDurationDaysAttribute(): int { return $this->start_date->diffInDays($this->end_date) + 1; }
    public function scopePublished(Builder $query): Builder { return $query->where('status','published'); }
    public function scopeDraft(Builder $query): Builder { return $query->where('status','draft'); }
    public function scopeActive(Builder $query): Builder { $today=now()->startOfDay(); return $query->where('status','published')->whereDate('start_date','<=',$today)->whereDate('end_date','>=',$today); }
    public function scopeUpcoming(Builder $query): Builder { return $query->where('status','published')->whereDate('start_date','>',now()->startOfDay()); }
    public function scopeRecentlyCompleted(Builder $query): Builder { $today=now()->startOfDay(); return $query->where('status','published')->whereDate('end_date','<',$today)->whereDate('end_date','>=',$today->subMonths(3)->startOfDay()); }
    public function scopeCompleted(Builder $query): Builder { return $query->where('status','published')->whereDate('end_date','<',now()->startOfDay()); }
}
