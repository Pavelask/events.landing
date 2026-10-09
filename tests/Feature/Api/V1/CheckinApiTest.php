<?php

namespace Tests\Feature\Api\V1;

use App\Models\AnonParticipant;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(): Event
    {
        return Event::create([
            'title' => 'Checkin Event '.str()->random(8),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'status' => 'published',
        ]);
    }

    private function authToken(): string
    {
        $user = User::create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => bcrypt('secret123'),
        ]);

        return $user->createToken('test')->plainTextToken;
    }

    public function test_checkin_requires_authentication(): void
    {
        $this->postJson('/api/v1/checkin/by-token', ['token' => 'anything'])
            ->assertUnauthorized();
    }

    public function test_checkin_marks_classic_participant_as_arrived(): void
    {
        $event = $this->makeEvent();

        $participant = Participant::create([
            'event_id' => $event->id,
            'name' => 'Иван Тестов',
            'source' => 'test',
            'checkin_token' => 'checkin-classic-1',
        ]);

        $this->withToken($this->authToken())
            ->postJson('/api/v1/checkin/by-token', ['token' => 'checkin-classic-1'])
            ->assertOk()
            ->assertJsonPath('data.is_checked_in', true)
            ->assertJsonPath('data.already_checked_in', false)
            ->assertJsonPath('data.status', 'arrived');

        $participant->refresh();
        $this->assertNotNull($participant->checked_in_at);
        $this->assertSame('arrived', $participant->status);
    }

    public function test_checkin_is_idempotent(): void
    {
        $event = $this->makeEvent();

        Participant::create([
            'event_id' => $event->id,
            'name' => 'Иван Тестов',
            'source' => 'test',
            'checkin_token' => 'checkin-classic-2',
        ]);

        $token = $this->authToken();

        $this->withToken($token)
            ->postJson('/api/v1/checkin/by-token', ['token' => 'checkin-classic-2'])
            ->assertOk()
            ->assertJsonPath('data.already_checked_in', false);

        $first = Participant::where('checkin_token', 'checkin-classic-2')->first()->checked_in_at;

        $this->withToken($token)
            ->postJson('/api/v1/checkin/by-token', ['token' => 'checkin-classic-2'])
            ->assertOk()
            ->assertJsonPath('data.already_checked_in', true)
            ->assertJsonPath('data.is_checked_in', true);

        $second = Participant::where('checkin_token', 'checkin-classic-2')->first()->checked_in_at;

        $this->assertTrue($first->equalTo($second));
    }

    public function test_checkin_works_for_anon_participant(): void
    {
        $event = $this->makeEvent();

        $anon = AnonParticipant::create([
            'event_id' => $event->id,
            'answer_id' => 'answer-checkin-1',
            'checkin_token' => 'checkin-anon-1',
            'status' => 'registered',
        ]);

        $this->withToken($this->authToken())
            ->postJson('/api/v1/checkin/by-token', ['token' => 'checkin-anon-1'])
            ->assertOk()
            ->assertJsonPath('data.type', 'anon')
            ->assertJsonPath('data.is_checked_in', true);

        $this->assertNotNull($anon->fresh()->checked_in_at);
    }

    public function test_checkin_returns_404_for_unknown_token(): void
    {
        $this->withToken($this->authToken())
            ->postJson('/api/v1/checkin/by-token', ['token' => 'no-such-token'])
            ->assertNotFound();
    }

    public function test_checkin_validates_token_presence(): void
    {
        $this->withToken($this->authToken())
            ->postJson('/api/v1/checkin/by-token', [])
            ->assertStatus(422);
    }
}
