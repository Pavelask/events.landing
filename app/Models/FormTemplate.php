<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormTemplate extends Model
{
    protected $fillable = [
        'name',
        'yandex_form_id',
        'questions',
    ];

    protected function casts(): array
    {
        return [
            'questions' => 'array',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
