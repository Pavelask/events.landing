<?php

namespace App\Filament\Resources\Guests\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GuestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Имя')->required(),
            TextInput::make('position')->label('Должность'),
            TextInput::make('organization')->label('Организация'),
            FileUpload::make('photo')
                ->label('Фото')
                ->image()
                ->disk('public')
                ->visibility('public')
                ->directory('guests')
                ->imagePreviewHeight('200')
                ->imageEditor(),
            RichEditor::make('description')
                ->label('Описание')
                ->columnSpanFull()
                ->extraInputAttributes(['style' => 'min-height: 150px;'])
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsDirectory('guests/content'),
        ]);
    }
}
