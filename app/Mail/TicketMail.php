<?php

namespace App\Mail;

use App\Mail\Concerns\RendersEmailTemplate;
use App\Models\AnonParticipant;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use RendersEmailTemplate;
    use SerializesModels;

    public function __construct(
        public Participant|AnonParticipant $participant,
        public string $ticketUrl
    ) {}

    public function envelope(): Envelope
    {
        $template = $this->resolveEmailTemplate('ticket');

        if ($template) {
            return $this->templateEnvelope($template, $this->eventForRecipient($this->participant), $this->participant);
        }

        return new Envelope(
            subject: "Ваш билет: {$this->participant->event->title}",
        );
    }

    public function content(): Content
    {
        $template = $this->resolveEmailTemplate('ticket');

        if ($template) {
            return $this->templateContent($template, $this->eventForRecipient($this->participant), $this->participant);
        }

        return new Content(
            view: 'emails.ticket',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
