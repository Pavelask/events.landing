<?php

namespace App\Filament\Resources\DocumentTemplates\Schemas;

use App\Services\DocxConverterService;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
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
                    ->helperText('Загрузите .docx файл как образец. Скопируйте текст в редактор ниже.'),

                \Filament\Actions\Action::make('convertDocx')
                    ->label('Конвертировать .docx → HTML')
                    ->icon('heroicon-o-arrow-path')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->modalHeading('Конвертировать файл?')
                    ->modalDescription('Текущий текст в редакторе будет заменён на содержимое .docx файла')
                    ->action(function ($get, $set) {
                        $file = $get('docx_file');
                        if (empty($file)) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('Сначала загрузите .docx файл')
                                ->send();
                            return;
                        }

                        $disk = \Illuminate\Support\Facades\Storage::disk('public');
                        if (!$disk->exists($file)) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Файл не найден на диске')
                                ->send();
                            return;
                        }

                        $converter = app(DocxConverterService::class);
                        $fullPath = $disk->path($file);
                        $html = $converter->convertToHtml($fullPath);
                        $html = $converter->applyPlaceholders($html);

                        $set('content', $html ?: '<p></p>');

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Файл конвертирован')
                            ->send();
                    }),

                RichEditor::make('content')
                    ->label('Содержимое шаблона')
                    ->default('<p></p>')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold', 'italic', 'underline', 'strike',
                        'link', 'blockquote',
                        'bulletList', 'orderedList',
                        'h2', 'h3',
                        'attachFiles',
                        'undo', 'redo',
                    ])
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
