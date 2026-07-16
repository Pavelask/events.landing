<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class TiptapEditor extends Field
{
    protected string $view = 'forms.components.tiptap-editor';

    protected string | \Closure $placeholder = 'Введите текст...';

    protected bool | \Closure $showSourceToggle = true;

    public function placeholder(string | \Closure $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(): string
    {
        return $this->evaluate($this->placeholder);
    }

    public function showSourceToggle(bool | \Closure $showSourceToggle = true): static
    {
        $this->showSourceToggle = $showSourceToggle;

        return $this;
    }

    public function getShowSourceToggle(): bool
    {
        return $this->evaluate($this->showSourceToggle);
    }
}
