<?php

namespace App\Filament\Resources\DocumentTemplates\Schemas;

use App\Forms\Components\TiptapEditor;
use App\Models\FormTemplate;
use App\Services\DocumentTemplateVariableService;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentTemplateForm
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
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->dehydrated(),

                FileUpload::make('docx_file')
                    ->label('Загрузить .docx')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(10240)
                    ->directory('document-templates')
                    ->disk('public')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(fn (\Illuminate\Http\UploadedFile $file): string => $file->getClientOriginalName())
                    ->helperText('Загрузите .docx файл, затем нажмите «Конвертировать .docx» в шапке страницы.'),

                Select::make('form_template_id')
                    ->label('Форма (источник переменных)')
                    ->placeholder('Без формы — доступны только системные переменные')
                    ->options(fn () => FormTemplate::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->nullable()
                    ->helperText('Из вопросов этой формы в шаблон будут доступны переменные для вставки. Участник при генерации заполнит их своими ответами.'),

                TiptapEditor::make('content')
                    ->label('HTML-шаблон')
                    ->placeholder('Введите HTML-шаблон...')
                    ->columnSpanFull()
                    ->default('<p></p>')
                    ->variables(fn (callable $get) => DocumentTemplateVariableService::all(FormTemplate::find($get('form_template_id'))))
                    ->helperText('Вставляйте переменные кнопкой «{}» в тулбаре редактора. Системные: {{ full_name }}, {{ email }}, {{ phone }}, {{ event_title }}, {{ event_date }}, {{ current_date }}, {{ organization_name }}, {{ organization_inn }}. Поля выбранной формы — по кнопке.'),

                KeyValue::make('variables')
                    ->label('Переменные')
                    ->helperText('Описание доступных плейсхолдеров')
                    ->reorderable(),

                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),
            ])
            ->columns(2);
    }
}
