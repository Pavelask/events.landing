<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventDayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date?->toDateString(),
            'label' => $this->label,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'events' => ScheduleEventResource::collection($this->whenLoaded('events')),
        ];
    }
}
