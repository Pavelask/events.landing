<?php

namespace Tests\Feature\Api\V1;

use App\Models\AnonParticipant;
use App\Models\Event;
use App\Models\FormTemplate;
use App\Models\Participant;
use App\Services\YandexFormsApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(): Event
    {
        return Event::create([
            'title' => 'Ticket Event '.str()->random(8),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'status' => 'published',
        ]);
    }

    public function test_classic_ticket_by_token(): void
    {
        $event = $this->makeEvent();

        Participant::create([
            'event_id' => $event->id,
            'name' => 'Иван Тестов',
            'email' => 'ivan@example.com',
            'source' => 'test',
            'checkin_token' => 'classic-token-1',
        ]);

        $this->getJson('/api/v1/ticket/classic-token-1')
            ->assertOk()
            ->assertJsonPath('data.type', 'classic')
            ->assertJsonPath('data.token', 'classic-token-1')
            ->assertJsonPath('data.participant.name', 'Иван Тестов')
            ->assertJsonPath('data.event.slug', $event->slug);
    }

    public function test_anon_ticket_resolves_name_from_yandex_forms(): void
    {
        $this->mock(YandexFormsApi::class, function ($mock): void {
            $mock->shouldReceive('getAnswer')->once()->andReturn([
                'data' => [
                    ['label' => 'Имя', 'value' => 'Пётр Антонов'],
                    ['label' => 'email', 'value' => 'petr@example.com'],
                ],
            ]);
        });

        $event = $this->makeEvent();

        $formTemplate = FormTemplate::create([
            'name' => 'Anon Form',
            'yandex_form_id' => 'form-123',
            'questions' => [],
        ]);
        $event->formTemplate()->associate($formTemplate);
        $event->save();

        AnonParticipant::create([
            'event_id' => $event->id,
            'answer_id' => 'answer-1',
            'checkin_token' => 'anon-token-1',
            'status' => 'registered',
        ]);

        $this->getJson('/api/v1/ticket/anon-token-1')
            ->assertOk()
            ->assertJsonPath('data.type', 'anon')
            ->assertJsonPath('data.participant.name', 'Пётр Антонов')
            ->assertJsonPath('data.attributes.souvenir_given', false);
    }

    public function test_ticket_returns_404_for_unknown_token(): void
    {
        $this->getJson('/api/v1/ticket/no-such-token')->assertNotFound();
    }

    public function test_qr_returns_png_for_known_token(): void
    {
        $event = $this->makeEvent();
        $token = str()->random(40);

        Participant::create([
            'event_id' => $event->id,
            'name' => 'QR User',
            'source' => 'test',
            'checkin_token' => $token,
        ]);

        $response = $this->get('/api/v1/ticket/'.$token.'/qr');

        $response->assertOk();
        $this->assertStringContainsString('image/png', $response->headers->get('Content-Type'));
    }

    public function test_qr_returns_404_for_unknown_token(): void
    {
        $this->get('/api/v1/ticket/no-such-token/qr')->assertNotFound();
    }
}
