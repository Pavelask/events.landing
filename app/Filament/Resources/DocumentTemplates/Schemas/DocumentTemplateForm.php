<?php

namespace App\Filament\Resources\DocumentTemplates\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\KeyValue;
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

                CodeEditor::make('content')
                    ->label('HTML-шаблон')
                    ->language(Language::Html)
                    ->theme('github')
                    ->columnSpanFull()
                    ->rows(25)
                    ->default('<p></p>')
                    ->helperText('Используйте {{ variable_name }} для плейсхолдеров. Доступные: {{ full_name }}, {{ passport_series }}, {{ passport_number }}, {{ passport_issued_by }}, {{ registration_address }}, {{ phone }}, {{ email }}, {{ event_title }}, {{ event_date }}, {{ current_date }}, {{ organization_name }}, {{ organization_inn }}'),

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
