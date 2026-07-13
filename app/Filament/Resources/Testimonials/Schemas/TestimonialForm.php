<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('author_name')
                ->label('Автор отзыва')
                ->required()
                ->maxLength(255),

            RichEditor::make('content')
                ->label('Текст отзыва')
                ->required()
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
                ->maxLength(10000)
                ->columnSpanFull(),

            FileUpload::make('photo')
                ->label('Фото автора')
                ->disk('public')
                ->directory('testimonials')
                ->image()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'])
                ->imageEditor()
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1')
                ->imagePreviewHeight('150')
                ->maxSize(5000)
                ->helperText('Круглое фото, рекомендуется 300x300px'),

            Grid::make(2)
                ->schema([
                    Toggle::make('is_active')
                        ->label('Статус')
                        ->onIcon('heroicon-m-check-circle')
                        ->offIcon('heroicon-m-x-circle')
                        ->default(true)
                        ->helperText('Показывать отзыв на сайте'),

                    TextInput::make('sort_order')
                        ->label('Порядок отображения')
                        ->numeric()
                        ->default(0)
                        ->helperText('Меньшие значения отображаются первыми'),
                ]),
        ]);
    }
}
