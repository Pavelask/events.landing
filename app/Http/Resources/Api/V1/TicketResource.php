<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TicketResource extends JsonResource
{
    public function __construct(
        $resource,
        protected ?string $resolvedName = null,
        protected ?string $resolvedEmail = null,
        protected string $type = 'classic',
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $token = $this->checkin_token;

        return [
            'type' => $this->type,
            'token' => $token,
            'status' => $this->status,
            'is_checked_in' => $this->checked_in_at !== null,
            'checked_in_at' => $this->checked_in_at?->toIso8601String(),
            'participant' => [
                'id' => $this->id,
                'name' => $this->resolvedName,
                'email' => $this->resolvedEmail,
            ],
            'event' => [
                'slug' => $this->event?->slug,
                'title' => $this->event?->title,
                'start_date' => $this->event?->start_date?->toDateString(),
                'end_date' => $this->event?->end_date?->toDateString(),
                'venue_name' => $this->event?->venue_name,
                'venue_address' => $this->event?->venue_address,
                'poster_image' => $this->event?->poster_image
                    ? Storage::url($this->event->poster_image)
                    : null,
            ],
            'checkin_url' => $token ? route('checkin.handle', $token) : null,
            'qr_url' => $token ? url("/api/v1/ticket/{$token}/qr") : null,
            'ticket_url' => $token ? route('ticket.show', $token) : null,
            'attributes' => $this->when($this->type === 'anon', fn () => [
                'souvenir_given' => (bool) $this->souvenir_given,
                'documentation_given' => (bool) $this->documentation_given,
                'clothing_given' => (bool) $this->clothing_given,
            ]),
        ];
    }
}
