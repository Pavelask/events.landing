<?php

namespace App\Mail;

use App\Mail\Concerns\RendersEmailTemplate;
use App\Models\AnonParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use RendersEmailTemplate;
    use SerializesModels;

    public function __construct(
        public AnonParticipant $participant,
        public string $eventTitle,
    ) {}

    public function envelope(): Envelope
    {
        $template = $this->resolveEmailTemplate('registration-confirmation');

        if ($template) {
            return $this->templateEnvelope($template, $this->eventForRecipient($this->participant), $this->participant);
        }

        return new Envelope(
            subject: "Регистрация подтверждена: {$this->eventTitle}",
        );
    }

    public function content(): Content
    {
        $template = $this->resolveEmailTemplate('registration-confirmation');

        if ($template) {
            return $this->templateContent($template, $this->eventForRecipient($this->participant), $this->participant);
        }

        return new Content(
            view: 'emails.registration-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
