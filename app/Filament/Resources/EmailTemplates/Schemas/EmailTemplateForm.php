<?php

namespace App\Filament\Resources\EmailTemplates\Schemas;

use App\Forms\Components\TiptapEditor;
use App\Models\FormTemplate;
use App\Services\EmailTemplateVariableService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class EmailTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('key', Str::slug($state) ?: 'template')),

                TextInput::make('key')
                    ->label('Ключ')
                    ->helperText('Системные ключи: registration-confirmation, ticket, ticket-reminder, verification-code. Для рассылок — любое уникальное значение.')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->live()
                    ->dehydrated(),

                TextInput::make('subject')
                    ->label('Тема письма')
                    ->placeholder('Например: Регистрация подтверждена: {{ event_title }}')
                    ->maxLength(255)
                    ->required()
                    ->helperText('Можно использовать переменные, например {{ event_title }}'),

                Select::make('form_template_id')
                    ->label('Форма (источник переменных)')
                    ->placeholder('Без формы — доступны только системные переменные')
                    ->options(fn () => FormTemplate::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->nullable()
                    ->helperText('Из вопросов этой формы в шаблон будут доступны переменные для вставки. При отправке они заполнятся ответами участника.'),

                TiptapEditor::make('content')
                    ->label('HTML-шаблон')
                    ->placeholder('Введите HTML-шаблон письма...')
                    ->columnSpanFull()
                    ->default('<p></p>')
                    ->variables(fn (callable $get) => EmailTemplateVariableService::all(FormTemplate::find($get('form_template_id'))))
                    ->helperText('Вставляйте переменные кнопкой «{}» в тулбаре редактора. Системные: {{ full_name }}, {{ email }}, {{ phone }}, {{ event_title }}, {{ event_date }}, {{ event_url }}, {{ ticket_url }}, {{ verification_code }}, {{ venue_name }}, {{ venue_address }}, {{ current_date }}. Поля выбранной формы — по кнопке.'),

                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true)
                    ->columnSpan(1),
            ])
            ->columns(2);
    }
}
