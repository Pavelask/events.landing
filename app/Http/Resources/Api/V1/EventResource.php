<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'daily_start_time' => $this->daily_start_time,
            'daily_end_time' => $this->daily_end_time,
            'venue_name' => $this->venue_name,
            'venue_address' => $this->venue_address,
            'poster_image' => $this->poster_image ? Storage::url($this->poster_image) : null,
            'logo' => $this->logo ? Storage::url($this->logo) : null,
            'video_url' => $this->video_url,
            'is_registration_open' => $this->is_registration_open,
            'registration_type' => $this->registration_type,
            'registration_url' => $this->registration_url,
            'yandex_form_id' => $this->yandex_form_id,
            'media_image' => $this->media_image ? Storage::url($this->media_image) : null,
            'media_description' => $this->media_description,
            'is_media_visible' => $this->is_media_visible,
            'gallery' => is_array($this->gallery) ? collect($this->gallery)->map(fn ($img) => $img ? Storage::url($img) : null)->filter()->values()->all() : [],
            'gallery_external_url' => $this->gallery_external_url,
            'gallery_external_description' => $this->gallery_external_description,
            'is_gallery_external_visible' => $this->is_gallery_external_visible,
            'days' => EventDayResource::collection($this->whenLoaded('days')),
            'speakers' => SpeakerResource::collection($this->whenLoaded('speakers')),
            'guests' => GuestResource::collection($this->whenLoaded('guests')),
            'testimonials' => TestimonialResource::collection($this->whenLoaded('testimonials')),
            'faqs' => FaqResource::collection($this->whenLoaded('faqs')),
            'documents' => EventDocumentResource::collection($this->whenLoaded('documents')),
        ];
    }
}
