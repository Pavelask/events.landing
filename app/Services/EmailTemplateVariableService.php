<?php

namespace App\Services;

use App\Models\FormTemplate;

class EmailTemplateVariableService
{
    /**
     * Системные переменные, доступные в любом email-шаблоне.
     */
    public static function systemVariables(): array
    {
        return [
            'full_name' => 'ФИО участника',
            'email' => 'Email',
            'phone' => 'Телефон',
            'participant_id' => 'ID участника',
            'event_title' => 'Название мероприятия',
            'event_date' => 'Дата мероприятия (дд.мм.гггг)',
            'event_start_datetime' => 'Время начала мероприятия',
            'event_url' => 'Ссылка на сайт мероприятия',
            'ticket_url' => 'Ссылка на билет',
            'verification_code' => 'Код подтверждения',
            'venue_name' => 'Название площадки',
            'venue_address' => 'Адрес площадки',
            'current_date' => 'Текущая дата',
        ];
    }

    /**
     * Переменные из вопросов формы регистрации.
     */
    public static function formVariables(?FormTemplate $form): array
    {
        if (! $form) {
            return [];
        }

        $variables = [];

        foreach (($form->questions ?? []) as $question) {
            $slug = $question['slug'] ?? null;

            if ($slug) {
                $variables[$slug] = $question['label'] ?? $slug;
            }
        }

        return $variables;
    }

    /**
     * Полный список переменных для кнопки вставки в редактор.
     */
    public static function all(?FormTemplate $form): array
    {
        $items = [];

        foreach (self::systemVariables() as $key => $label) {
            $items[] = ['key' => $key, 'label' => $label, 'group' => 'Системные'];
        }

        foreach (self::formVariables($form) as $key => $label) {
            $items[] = ['key' => $key, 'label' => $label, 'group' => 'Поля формы'];
        }

        return $items;
    }

    /**
     * Тестовые данные для предпросмотра письма.
     */
    public static function previewData(): array
    {
        return [
            'full_name' => 'Иванов Иван Иванович',
            'email' => 'test@example.com',
            'phone' => '+7 (999) 123-45-67',
            'participant_id' => '42',
            'event_title' => 'Тестовое мероприятие',
            'event_date' => '2026-05-12',
            'event_start_datetime' => '2026-05-12 10:00',
            'event_url' => 'https://events.elprof.ru',
            'ticket_url' => 'https://events.elprof.ru/ticket/test-token',
            'verification_code' => '123456',
            'venue_name' => 'Конгресс-центр',
            'venue_address' => 'Москва, ул. Примерная, 1',
            'current_date' => now()->format('d.m.Y'),
        ];
    }
}
