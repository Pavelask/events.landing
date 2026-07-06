<?php

namespace App\Filament\Resources\FormTemplates;

use App\Filament\Resources\FormTemplates\Pages\CreateFormTemplate;
use App\Filament\Resources\FormTemplates\Pages\EditFormTemplate;
use App\Filament\Resources\FormTemplates\Pages\ListFormTemplates;
use App\Filament\Resources\FormTemplates\Schemas\FormTemplateForm;
use App\Models\FormTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FormTemplateResource extends Resource
{
    protected static ?string $model = FormTemplate::class;

    protected static string|UnitEnum|null $navigationGroup = 'Настройки';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Шаблоны форм';

    public static function form(Schema $schema): Schema
    {
        return FormTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->date('d.m.Y')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлён')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFormTemplates::route('/'),
            'create' => CreateFormTemplate::route('/create'),
            'edit' => EditFormTemplate::route('/{record}/edit'),
        ];
    }
}
