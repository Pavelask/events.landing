<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailTemplate extends Model
{
    protected $fillable = [
        'key',
        'name',
        'subject',
        'content',
        'variables',
        'form_template_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function newsletters(): HasMany
    {
        return $this->hasMany(Newsletter::class);
    }

    public function formTemplate(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class);
    }

    /**
     * Ищет активный шаблон по системному ключу.
     */
    public static function forKey(string $key): ?self
    {
        return static::query()->where('key', $key)->where('is_active', true)->first();
    }

    /**
     * Рендерит тему/тело письма, подставляя переменные.
     */
    public function renderSubject(array $vars): string
    {
        $subject = (string) $this->subject;

        foreach ($vars as $key => $value) {
            $subject = str_replace("{{ {$key} }}", (string) $value, $subject);
        }

        return $subject;
    }

    public function renderContent(array $vars): string
    {
        $content = (string) $this->content;

        foreach ($vars as $key => $value) {
            $content = str_replace("{{ {$key} }}", (string) $value, $content);
        }

        // Безопасный минимум: тело письма рендерится как есть (HTML от Tiptap).
        return $content;
    }
}
