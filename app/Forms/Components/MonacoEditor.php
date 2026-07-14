<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class MonacoEditor extends Field
{
    protected string $view = 'forms.components.monaco-editor';

    protected string | \Closure $language = 'html';

    protected string | \Closure | null $theme = null;

    protected bool | \Closure $showPreview = true;

    public function language(string | \Closure $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->evaluate($this->language);
    }

    public function theme(string | \Closure | null $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->evaluate($this->theme);
    }

    public function showPreview(bool | \Closure $showPreview = true): static
    {
        $this->showPreview = $showPreview;

        return $this;
    }

    public function getShowPreview(): bool
    {
        return $this->evaluate($this->showPreview);
    }
}
