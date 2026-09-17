<?php

namespace App\Mail\Concerns;

use App\Models\AnonParticipant;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Participant;
use App\Services\EmailRenderer;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

trait RendersEmailTemplate
{
    /**
     * Активный шаблон по системному ключу, либо null (тогда — классический Blade).
     */
    protected function resolveEmailTemplate(string $key): ?EmailTemplate
    {
        return EmailTemplate::forKey($key);
    }

    protected function eventForRecipient(Participant|AnonParticipant $recipient): Event
    {
        return $recipient->event()->firstOrFail();
    }

    protected function templateEnvelope(EmailTemplate $template, Event $event, Participant|AnonParticipant $recipient): Envelope
    {
        $subject = app(EmailRenderer::class)->render($template, $event, $recipient)['subject'];

        return new Envelope(subject: $subject);
    }

    protected function templateContent(EmailTemplate $template, Event $event, Participant|AnonParticipant $recipient): Content
    {
        $html = app(EmailRenderer::class)->render($template, $event, $recipient)['html'];

        return new Content(view: 'emails.wrapper', with: ['html' => $html]);
    }
}
