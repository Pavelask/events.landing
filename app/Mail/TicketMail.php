<?php

namespace App\Mail;

use App\Models\AnonParticipant;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Participant|AnonParticipant $participant,
        public string $ticketUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Ваш билет: {$this->participant->event->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
