<?php

namespace Tests\Feature\Api\V1;

use App\Models\Event;
use App\Models\EventDay;
use App\Models\ScheduleEvent;
use App\Models\Speaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(array $attributes = []): Event
    {
        return Event::create(array_merge([
            'title' => 'Event '.str()->random(8),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'status' => 'published',
        ], $attributes));
    }

    public function test_index_returns_only_published_events_by_default(): void
    {
        $published = $this->makeEvent();
        $this->makeEvent(['status' => 'draft']);

        $this->getJson('/api/v1/events')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $published->slug);
    }

    public function test_index_filters_by_status(): void
    {
        $draft = $this->makeEvent(['status' => 'draft']);
        $this->makeEvent(['status' => 'published']);

        $this->getJson('/api/v1/events?status=draft')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $draft->slug);
    }

    public function test_show_returns_nested_resources(): void
    {
        $event = $this->makeEvent();

        $day = EventDay::create([
            'event_id' => $event->id,
            'date' => now()->toDateString(),
            'label' => 'Day 1',
        ]);

        $speaker = Speaker::factory()->create();

        ScheduleEvent::create([
            'event_day_id' => $day->id,
            'speaker_id' => $speaker->id,
            'title' => 'Keynote talk',
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        $event->speakers()->attach($speaker->id);

        $this->getJson('/api/v1/events/'.$event->slug)
            ->assertOk()
            ->assertJsonPath('data.slug', $event->slug)
            ->assertJsonCount(1, 'data.days')
            ->assertJsonPath('data.days.0.events.0.title', 'Keynote talk')
            ->assertJsonCount(1, 'data.speakers')
            ->assertJsonPath('data.speakers.0.name', $speaker->name);
    }

    public function test_show_returns_404_for_unknown_slug(): void
    {
        $this->getJson('/api/v1/events/does-not-exist')->assertNotFound();
    }

    public function test_schedule_endpoint_returns_days_with_events(): void
    {
        $event = $this->makeEvent();

        $day = EventDay::create([
            'event_id' => $event->id,
            'date' => now()->toDateString(),
            'label' => 'Day 1',
        ]);

        ScheduleEvent::create([
            'event_day_id' => $day->id,
            'title' => 'Registration',
            'start_time' => '08:00',
            'end_time' => '09:00',
        ]);

        $this->getJson('/api/v1/events/'.$event->slug.'/schedule')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.events.0.title', 'Registration');
    }

    public function test_gallery_endpoint_returns_external_gallery_payload(): void
    {
        $event = $this->makeEvent([
            'gallery' => ['events/gallery/a.jpg', 'events/gallery/b.jpg'],
            'gallery_external_url' => 'https://disk.example.com/gallery',
            'is_gallery_external_visible' => true,
        ]);

        $this->getJson('/api/v1/events/'.$event->slug.'/gallery')
            ->assertOk()
            ->assertJsonCount(2, 'data.gallery')
            ->assertJsonPath('data.external.url', 'https://disk.example.com/gallery')
            ->assertJsonPath('data.external.has_external_gallery', true);
    }
}
