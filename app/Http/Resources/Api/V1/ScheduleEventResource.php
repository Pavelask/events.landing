<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ScheduleEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time?->format('H:i'),
            'end_time' => $this->end_time?->format('H:i'),
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'is_break' => $this->is_break,
            'speaker' => $this->whenLoaded('speaker', fn () => [
                'id' => $this->speaker->id,
                'name' => $this->speaker->name,
                'position' => $this->speaker->position,
                'photo' => $this->speaker->photo ? Storage::url($this->speaker->photo) : null,
            ]),
        ];
    }
}
