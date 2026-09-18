<?php

namespace App\Mail;

use App\Models\AnonParticipant;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Participant;
use App\Services\EmailRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
class TemplateMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public string $renderedHtml;

    public string $renderedSubject;

    public string $eventTitle;

    public function __construct(
        EmailTemplate $template,
        Event $event,
        Participant|AnonParticipant $recipient,
    ) {
        $renderer = app(EmailRenderer::class);
        $rendered = $renderer->render($template, $event, $recipient);

        $this->eventTitle = $event->title;
        $this->renderedSubject = $rendered['subject'];
        $this->renderedHtml = $rendered['html'];
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->renderedSubject);
    }

    public function content(): Content
    {
        return new Content(
            // Оборачиваем фрагмент из Tiptap в единый каркас письма.
            view: 'emails.wrapper',
            with: ['html' => $this->renderedHtml, 'heading' => $this->eventTitle],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
