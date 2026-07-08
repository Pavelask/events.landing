<?php

namespace App\Mail;

use App\Models\AnonParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public AnonParticipant $participant,
        public string $eventTitle,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Регистрация подтверждена: {$this->eventTitle}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
