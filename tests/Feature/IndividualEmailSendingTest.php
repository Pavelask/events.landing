<?php

namespace Tests\Feature;

use App\Filament\Resources\EmailTemplates\Pages\EditEmailTemplate;
use App\Mail\TemplateMail;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class IndividualEmailSendingTest extends TestCase
{
    use RefreshDatabase;

    protected function template(): EmailTemplate
    {
        return EmailTemplate::create([
            'key' => 'test-' . str()->random(8),
            'name' => 'Ручная отправка',
            'subject' => 'Привет, {{ full_name }}!',
            'content' => '<p>Билет: {{ ticket_url }}</p>',
            'is_active' => true,
        ]);
    }

    public function test_header_action_sends_test_mail_to_self(): void
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($admin);

        $template = $this->template();

        Livewire::test(EditEmailTemplate::class, ['record' => $template->getRouteKey()])
            ->mountAction('sendTest')
            ->callMountedAction()
            ->assertHasNoErrors();

        Mail::assertQueued(TemplateMail::class, fn (TemplateMail $mail) => $mail->hasTo('admin@example.com'));
    }

    public function test_template_mail_renders_for_single_participant_withowning_event(): void
    {
        Mail::fake();

        $event = Event::create(['title' => 'E2E событие', 'start_date' => now(), 'end_date' => now()->addDay()]);
        $participant = Participant::create([
            'event_id' => $event->id,
            'name' => 'Ivan Testov',
            'email' => 'ivan@example.com',
            'checkin_token' => 'tok-123',
            'source' => 'manual',
        ]);
        $template = $this->template();

        Mail::to($participant->email)->send(new TemplateMail($template, $event, $participant));

        Mail::assertQueued(TemplateMail::class, fn (TemplateMail $mail) => $mail->hasTo('ivan@example.com'));
    }
}