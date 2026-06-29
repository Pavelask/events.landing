<?php

namespace App\Filament\Resources\EventDays\Schemas;

use App\Models\Event;
use App\Models\ScheduleEvent;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventDayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('event_id')
                ->label('Мероприятие')
                ->relationship('event', 'title')
                ->searchable()
                ->preload()
                ->required()
                ->default(fn () => request()->get('event_id'))
                ->columnSpanFull(),

            Grid::make(4)->schema([
                DatePicker::make('date')
                    ->label('Дата')
                    ->required()
                    ->native(false)
                    ->unique(
                        table: 'event_days',
                        column: 'date',
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            $eventId = request()->input('data.event_id');
                            if (!$eventId && $recordId = request()->route('record')) {
                                $eventId = \App\Models\EventDay::find($recordId)?->event_id;
                            }
                            if ($eventId) {
                                $rule->where('event_id', $eventId);
                            }
                            return $rule;
                        },
                    ),
                TextInput::make('label')
                    ->label('Название дня')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Например: День заезда'),
                TextInput::make('sort_order')
                    ->label('Порядок')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),
            ])->columnSpanFull(),

            RichEditor::make('description')
                ->label('Описание дня')
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsDirectory('events/days/content')
                ->extraInputAttributes(['style' => 'min-height: 100px;'])
                ->placeholder('Краткое описание программы дня')
                ->columnSpanFull(),

            Section::make('События дня')
                ->schema([
                    Repeater::make('events')
                        ->relationship('events')
                        ->label('Расписание дня')
                        ->addActionLabel('Добавить событие')
                        ->reorderableWithDragAndDrop()
                        ->collapsible()
                        ->collapsed()
                        ->cloneAction(
                            fn () => \Filament\Actions\Action::make('clone')
                                ->label('Клонировать')
                                ->icon('heroicon-o-document-duplicate')
                        )
                        ->schema([
                            Grid::make(4)->schema([
                                TextInput::make('start_time')
                                    ->label('Начало')
                                    ->mask('99:99')
                                    ->placeholder('09:00')
                                    ->required()
                                    ->rules(['regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'])
                                    ->dehydrateStateUsing(fn ($state) => $state ? (strlen($state) === 4 ? substr($state, 0, 2) . ':' . substr($state, 2) : $state) : null),
                                TextInput::make('end_time')
                                    ->label('Конец')
                                    ->mask('99:99')
                                    ->placeholder('10:00')
                                    ->required()
                                    ->rules([
                                        'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/',
                                    ])
                                    ->dehydrateStateUsing(fn ($state) => $state ? (strlen($state) === 4 ? substr($state, 0, 2) . ':' . substr($state, 2) : $state) : null),
                                FileUpload::make('icon_image')
                                    ->label('Иконка (фото)')
                                    ->image()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('events/schedule-icons')
                                    ->imagePreviewHeight('100')
                                    ->imageEditor(),
                                ColorPicker::make('color')
                                    ->label('Цвет точки'),
                            ]),
                            TextInput::make('title')
                                ->label('Название')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Textarea::make('description')
                                ->label('Описание')
                                ->rows(2)
                                ->columnSpanFull(),
                            Grid::make(4)->schema([
                                Select::make('speaker_id')
                                    ->label('Спикер')
                                    ->relationship('speaker', 'name')
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('location')
                                    ->label('Место')
                                    ->maxLength(255)
                                    ->placeholder('Название зала'),
                                Toggle::make('is_break')
                                    ->label('Перерыв')
                                    ->default(false),
                                TextInput::make('sort_order')
                                    ->label('Порядок')
                                    ->numeric()
                                    ->default(0),
                            ]),
                        ])
                        ->orderColumn('sort_order')
                        ->itemLabel(fn (array $state) => ($state['start_time'] ?? '--:--') . ' ' . ($state['title'] ?? 'Новое событие')),
                ])
                ->columnSpanFull(),
        ]);
    }
}
