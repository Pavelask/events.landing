<?php

namespace App\Filament\Resources\FormTemplates\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FormTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Основная информация')
                    ->schema([
                        TextInput::make('name')
                            ->label('Название шаблона')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('yandex_form_id')
                            ->label('ID формы Яндекс')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Идентификатор формы из Яндекс Форм'),
                    ]),

                Section::make('Вопросы формы')
                    ->schema([
                        Repeater::make('questions')
                            ->label('Вопросы')
                            ->addActionLabel('Добавить вопрос')
                            ->defaultItems(0)
                            ->reorderable()
                            ->schema([
                                TextInput::make('label')
                                    ->label('Название вопроса')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                                Select::make('type')
                                    ->label('Тип поля')
                                    ->options([
                                        'text' => 'Текст',
                                        'textarea' => 'Текстовая область',
                                        'select' => 'Выпадающий список',
                                        'radio' => 'Радио-кнопка',
                                        'checkbox' => 'Чекбокс',
                                        'date' => 'Дата',
                                    ])
                                    ->default('text')
                                    ->required()
                                    ->live(),
                                Toggle::make('required')
                                    ->label('Обязательное')
                                    ->default(false),
                                Textarea::make('options')
                                    ->label('Варианты ответа (через запятую)')
                                    ->placeholder('Вариант 1, Вариант 2, Вариант 3')
                                    ->rows(3)
                                    ->visible(fn (callable $get) => in_array($get('type'), ['select', 'radio', 'checkbox']))
                                    ->dehydrateStateUsing(function ($state) {
                                        if (is_array($state)) {
                                            return $state;
                                        }
                                        return $state ? array_map('trim', explode(',', $state)) : [];
                                    }),
                                Toggle::make('searchable')
                                    ->label('Показывать поиск')
                                    ->default(false)
                                    ->visible(fn (callable $get) => $get('type') === 'select'),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->helperText('Уникальный идентификатор вопроса (латиница, дефисы)')
                                    ->regex('/^[a-z0-9\-]+$/'),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['label'] ?? 'Новый вопрос'),
                    ]),
            ]);
    }
}
