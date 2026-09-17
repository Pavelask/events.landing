<?php

namespace App\Mail;

use App\Mail\Concerns\RendersEmailTemplate;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use RendersEmailTemplate;
    use SerializesModels;

    public function __construct(
        public Participant $participant
    ) {}

    public function envelope(): Envelope
    {
        $template = $this->resolveEmailTemplate('verification-code');

        if ($template) {
            return $this->templateEnvelope($template, $this->eventForRecipient($this->participant), $this->participant);
        }

        return new Envelope(
            subject: 'Код подтверждения для восстановления билета',
        );
    }

    public function content(): Content
    {
        $template = $this->resolveEmailTemplate('verification-code');

        if ($template) {
            return $this->templateContent($template, $this->eventForRecipient($this->participant), $this->participant);
        }

        return new Content(
            view: 'emails.verification-code',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
