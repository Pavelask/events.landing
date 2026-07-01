<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReminderMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Participant $participant,
        public string $ticketUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Напоминание: {$this->participant->event->title} — завтра!",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
