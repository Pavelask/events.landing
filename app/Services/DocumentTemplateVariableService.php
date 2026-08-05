<?php

namespace App\Services;

use App\Models\FormTemplate;

class DocumentTemplateVariableService
{
    public static function systemVariables(): array
    {
        return [
            'full_name' => 'ФИО участника',
            'email' => 'Email',
            'phone' => 'Телефон',
            'event_title' => 'Название мероприятия',
            'event_date' => 'Дата мероприятия',
            'current_date' => 'Текущая дата',
            'organization_name' => 'Название организации',
            'organization_inn' => 'ИНН организации',
        ];
    }

    public static function formVariables(?FormTemplate $form): array
    {
        if (!$form) {
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
}