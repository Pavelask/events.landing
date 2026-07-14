<?php

namespace App\Filament\Resources\DocumentTemplates\Schemas;

use App\Services\DocxConverterService;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
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
                    ->helperText('Загрузите .docx файл как образец для копирования текста в редактор ниже.'),

                RichEditor::make('content')
                    ->label('Содержимое шаблона')
                    ->default('<p></p>')
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('document-templates/attachments')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->helperText('Используйте {{ variable_name }} для плейсхолдеров. Доступные: {{ full_name }}, {{ passport_series }}, {{ passport_number }}, {{ passport_issued_by }}, {{ registration_address }}, {{ phone }}, {{ email }}, {{ event_title }}, {{ event_date }}, {{ current_date }}, {{ organization_name }}, {{ organization_inn }}'),

                Textarea::make('raw_html')
                    ->label('Или вставьте HTML напрямую')
                    ->placeholder('<p>Текст согласия...</p>')
                    ->rows(10)
                    ->columnSpanFull()
                    ->dehydrated(false)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        if (!empty($state)) {
                            $set('content', $state);
                        }
                    }),

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
