<?php

namespace App\Services;

use App\Models\AnonParticipant;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Support\Collection;

class EmailRenderer
{
    /**
     * Строит переменные для конкретного получателя.
     */
    public function variablesFor(Event $event, Participant|AnonParticipant $recipient, ?EmailTemplate $template = null): Collection
    {
        $answers = method_exists($recipient, 'getAttribute') && is_array($recipient->answers ?? null)
            ? $recipient->answers
            : [];

        $ticketUrl = $recipient->checkin_token
            ? route('ticket.show', $recipient->checkin_token)
            : '';

        $variables = collect([
            'full_name' => $recipient->name ?? ($answers['full_name'] ?? ''),
            'email' => $recipient->email ?? ($answers['email'] ?? ''),
            'phone' => $recipient->phone ?? ($answers['phone'] ?? ''),
            'participant_id' => $recipient->id,
            'event_title' => $event->title,
            'event_date' => $event->start_date?->format('d.m.Y') ?? '',
            'event_start_datetime' => $event->start_date?->format('d.m.Y H:i') ?? '',
            'event_url' => $event->exists ? route('event.show', $event) : url('/'),
            'ticket_url' => $ticketUrl,
            'verification_code' => $recipient->verification_code ?? '',
            'venue_name' => $event->venue_name ?? '',
            'venue_address' => $event->venue_address ?? '',
            'current_date' => now()->format('d.m.Y'),
        ]);

        foreach (($template?->formTemplate?->questions ?? []) as $question) {
            $slug = $question['slug'] ?? null;

            if ($slug) {
                $variables[$slug] = $this->answerValue($recipient, $slug, $answers);
            }
        }

        return $variables;
    }

    /**
     * Значение ответа участника по слагу вопроса.
     */
    private function answerValue(Participant|AnonParticipant $recipient, string $slug, array $answers): string
    {
        if (isset($answers[$slug])) {
            $value = $answers[$slug];

            return match (true) {
                is_array($value) => implode(', ', $value),
                is_bool($value) => $value ? 'Да' : 'Нет',
                default => (string) $value,
            };
        }

        return '';
    }

    /**
     * Рендерит тему и тело письма по шаблону для конкретного получателя.
     *
     * @return array{subject: string, html: string}
     */
    public function render(EmailTemplate $template, Event $event, Participant|AnonParticipant $recipient): array
    {
        $variables = $this->variablesFor($event, $recipient, $template)->toArray();

        return [
            'subject' => $template->renderSubject($variables),
            'html' => $template->renderContent($variables),
        ];
    }

    /**
     * Тестовые данные для предпросмотра шаблона в админке.
     */
    public function preview(EmailTemplate $template): array
    {
        $variables = EmailTemplateVariableService::previewData();

        foreach (($template->formTemplate?->questions ?? []) as $question) {
            $slug = $question['slug'] ?? null;

            if ($slug) {
                $variables[$slug] = 'Тестовое значение';
            }
        }

        return [
            'subject' => $template->renderSubject($variables),
            'html' => $template->renderContent($variables),
        ];
    }
}
