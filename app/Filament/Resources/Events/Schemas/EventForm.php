<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Models\Event;
use App\Models\Faq;
use App\Models\Guest;
use App\Models\Speaker;
use App\Models\Testimonial;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Tabs::make('Мероприятие')
                ->tabs([
                    Tab::make('Основное')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Общая информация')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('title')
                                            ->label('Название')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                                        TextInput::make('slug')
                                            ->label('URL')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(
                                                table: Event::class,
                                                column: 'slug',
                                                ignorable: fn ($record) => $record,
                                            ),
                                        DatePicker::make('start_date')
                                            ->label('Дата начала')
                                            ->required()
                                            ->native(false)
                                            ->closeOnDateSelection(),
                                        DatePicker::make('end_date')
                                            ->label('Дата окончания')
                                            ->required()
                                            ->native(false)
                                            ->closeOnDateSelection()
                                            ->afterOrEqual('start_date'),
                                        TextInput::make('daily_start_time')
                                            ->label('Начало дня')
                                            ->mask('99:99')
                                            ->placeholder('09:00')
                                            ->rules(['regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'])
                                            ->dehydrateStateUsing(fn ($state) => $state ? (strlen($state) === 4 ? substr($state, 0, 2) . ':' . substr($state, 2) : $state) : null),
                                        TextInput::make('daily_end_time')
                                            ->label('Конец дня')
                                            ->mask('99:99')
                                            ->placeholder('18:00')
                                            ->rules(['regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'])
                                            ->dehydrateStateUsing(fn ($state) => $state ? (strlen($state) === 4 ? substr($state, 0, 2) . ':' . substr($state, 2) : $state) : null),
                                        Select::make('timezone')
                                            ->label('Часовой пояс')
                                            ->options([
                                                'Europe/Kaliningrad' => 'Калининград (UTC+2)',
                                                'Europe/Moscow' => 'Москва, Крым, Донбасс (UTC+3)',
                                                'Europe/Samara' => 'Самара (UTC+4)',
                                                'Asia/Yekaterinburg' => 'Екатеринбург (UTC+5)',
                                                'Asia/Omsk' => 'Омск (UTC+6)',
                                                'Asia/Krasnoyarsk' => 'Красноярск (UTC+7)',
                                                'Asia/Irkutsk' => 'Иркутск (UTC+8)',
                                                'Asia/Yakutsk' => 'Якутск (UTC+9)',
                                                'Asia/Vladivostok' => 'Владивосток (UTC+10)',
                                                'Asia/Magadan' => 'Магадан (UTC+11)',
                                                'Asia/Kamchatka' => 'Камчатка (UTC+12)',
                                            ])
                                            ->default('Europe/Moscow')
                                            ->searchable(),
                                        Select::make('status')
                                            ->label('Статус')
                                            ->options([
                                                'draft' => 'Черновик',
                                                'published' => 'Опубликовано',
                                                'active' => 'Идёт сейчас',
                                                'completed' => 'Завершено',
                                                'archived' => 'Архив',
                                                'cancelled' => 'Отменено',
                                            ])
                                            ->default('draft'),
                                    ]),
                                    RichEditor::make('description')
                                        ->label('Описание мероприятия')
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('events/content')
                                        ->extraInputAttributes(['style' => 'min-height: 150px;'])
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Регистрация')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('registration_type')
                                            ->label('Тип регистрации')
                                            ->options(['none' => 'Нет', 'external' => 'Внешняя ссылка', 'yandex' => 'Яндекс Форма'])
                                            ->default('none')
                                            ->required()
                                            ->live(),
                                        Toggle::make('is_registration_open')->label('Регистрация открыта')->default(false),
                                    ]),
                                    TextInput::make('registration_url')
                                        ->label('Ссылка регистрации')
                                        ->url()
                                        ->placeholder('https://...')
                                        ->visible(fn (callable $get) => $get('registration_type') === 'external')
                                        ->default(null)
                                        ->columnSpanFull(),
                                    TextInput::make('yandex_form_url')
                                        ->label('HTML код Яндекс Формы')
                                        ->placeholder('<iframe src="..." ...></iframe>')
                                        ->visible(fn (callable $get) => $get('registration_type') === 'yandex')
                                        ->default(null)
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Социальные сети')
                                ->schema([
                                    KeyValue::make('social_links')
                                        ->label('Социальные сети')
                                        ->addActionLabel('Добавить соцсеть')
                                        ->keyLabel('Платформа')
                                        ->valueLabel('URL')
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Место проведения')
                                ->schema([
                                    TextInput::make('venue_name')->label('Название площадки'),
                                    TextInput::make('venue_address')->label('Адрес')->columnSpanFull(),
                                    Grid::make(2)->schema([
                                        TextInput::make('venue_lat')->label('Широта')->numeric()->minValue(-90)->maxValue(90)->step(0.000001),
                                        TextInput::make('venue_lng')->label('Долгота')->numeric()->minValue(-180)->maxValue(180)->step(0.000001),
                                    ]),
                                    Textarea::make('venue_how_to_get')->label('Как добраться')->rows(3)->columnSpanFull(),
                                    Grid::make(2)->schema([
                                        TextInput::make('contact_email')->label('Email')->email(),
                                        TextInput::make('contact_phone')
                                            ->label('Телефон')
                                            ->regex('/^(?:\+7|8)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}$/')
                                            ->validationAttribute('телефон'),
                                    ]),
                                ]),

                            Section::make('Политика конфиденциальности')
                                ->schema([
                                    Toggle::make('show_privacy_section')
                                        ->label('Показывать на сайте')
                                        ->default(false)
                                        ->live()
                                        ->columnSpanFull(),
                                    RichEditor::make('privacy_policy')
                                        ->label('Политика конфиденциальности')
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('events/privacy')
                                        ->extraInputAttributes(['style' => 'min-height: 150px;'])
                                        ->visible(fn (callable $get) => $get('show_privacy_section'))
                                        ->columnSpanFull(),
                                    RichEditor::make('personal_data_consent')
                                        ->label('Согласие на обработку персональных данных')
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('events/privacy')
                                        ->extraInputAttributes(['style' => 'min-height: 150px;'])
                                        ->visible(fn (callable $get) => $get('show_privacy_section'))
                                        ->columnSpanFull(),
                                ])
                                ->collapsible()
                                ->collapsed(),
                        ]),

                    Tab::make('Медиа')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make('poster_image')
                                ->label('Постер')
                                ->image()
                                ->disk('public')
                                ->visibility('public')
                                ->directory('events/posters')
                                ->imagePreviewHeight('200')
                                ->imageEditor(),
                            FileUpload::make('logo')
                                ->label('Логотип')
                                ->image()
                                ->disk('public')
                                ->visibility('public')
                                ->directory('events/logos')
                                ->imageEditor(),
                            TextInput::make('video_url')->label('Ссылка на видео')->url()->placeholder('https://...'),

                            Section::make('Медиа-контент')
                                ->schema([
                                    Toggle::make('is_media_visible')
                                        ->label('Показывать на сайте')
                                        ->default(false),
                                    FileUpload::make('media_image')
                                        ->label('Фотография')
                                        ->image()
                                        ->disk('public')
                                        ->visibility('public')
                                        ->directory('events/media')
                                        ->imagePreviewHeight('200')
                                        ->imageEditor(),
                                    RichEditor::make('media_description')
                                        ->label('Описание')
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('events/media/content')
                                        ->extraInputAttributes(['style' => 'min-height: 175px;'])
                                        ->columnSpanFull(),
                                ])
                                ->collapsible()
                                ->collapsed()
                                ->columnSpanFull(),

                            FileUpload::make('gallery')
                                ->label('Галерея')
                                ->multiple()
                                ->image()
                                ->disk('public')
                                ->visibility('public')
                                ->directory('events/gallery')
                                ->reorderable()
                                ->panelLayout('grid')
                                ->imageEditor()
                                ->columnSpanFull(),
                        ]),

                    Tab::make('FAQ')
                        ->icon('heroicon-o-question-mark-circle')
                        ->schema([
                            Repeater::make('eventFaqs')
                                ->relationship('eventFaqs')
                                ->label('Вопросы и ответы')
                                ->addActionLabel('Добавить вопрос')
                                ->schema([
                                    TextInput::make('id')->hidden(),
                                    Select::make('faq_id')
                                        ->relationship('faq', 'question')
                                        ->label('Вопрос')
                                        ->searchable()
                                        ->preload()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                    TextInput::make('sort_order')->label('Порядок')->numeric()->default(0),
                                ])
                                ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): ?array => filled($data['faq_id'] ?? null) ? $data : null)
                                ->mutateRelationshipDataBeforeSaveUsing(fn (array $data): ?array => filled($data['faq_id'] ?? null) ? $data : null)
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->itemLabel(fn (array $state): string => Faq::find($state['faq_id'] ?? null)?->question ?? 'Новый вопрос')
                                ->columnSpanFull(),
                        ]),

                    Tab::make('Документы')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Repeater::make('documents')
                                ->relationship('documents')
                                ->label('Документы')
                                ->addActionLabel('Добавить документ')
                                ->schema([
                                    TextInput::make('id')->hidden(),
                                    TextInput::make('title')->label('Название документа')->columnSpanFull(),
                                    FileUpload::make('file_path')
                                        ->label('Файл')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->directory('events/documents')
                                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                                    TextInput::make('sort_order')->label('Порядок')->numeric()->default(0),
                                ])
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->itemLabel(fn (array $state) => $state['title'] ?? 'Новый документ')
                                ->columnSpanFull(),
                        ]),

                    Tab::make('Спикеры')
                        ->icon('heroicon-o-users')
                        ->schema([
                            Repeater::make('eventSpeakers')
                                ->relationship('eventSpeakers')
                                ->label('')
                                ->addActionLabel('Добавить спикера')
                                ->schema([
                                    Grid::make(4)->schema([
                                        Select::make('speaker_id')
                                            ->label('Спикер')
                                            ->relationship('speaker', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                        TextInput::make('sort_order')
                                            ->label('Порядок')
                                            ->numeric()
                                            ->default(0),
                                        Toggle::make('is_visible')
                                            ->label('Показывать на сайте')
                                            ->default(true),
                                        Toggle::make('is_keynote')
                                            ->label('Ключевой спикер (VIP)')
                                            ->default(false),
                                    ]),
                                ])
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->itemLabel(fn (array $state): string => Speaker::find($state['speaker_id'] ?? null)?->name ?? 'Новый спикер')
                                ->columnSpanFull(),
                        ]),

                    Tab::make('Гости')
                        ->icon('heroicon-o-user-group')
                        ->schema([
                            Repeater::make('eventGuests')
                                ->relationship('eventGuests')
                                ->label('')
                                ->addActionLabel('Добавить гостя')
                                ->schema([
                                    Grid::make(4)->schema([
                                        Select::make('guest_id')
                                            ->label('Гость')
                                            ->relationship('guest', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                        TextInput::make('sort_order')
                                            ->label('Порядок')
                                            ->numeric()
                                            ->default(0),
                                        Toggle::make('is_visible')
                                            ->label('Показывать на сайте')
                                            ->default(true),
                                        Toggle::make('is_keynote')
                                            ->label('Ключевой гость (VIP)')
                                            ->default(false),
                                    ]),
                                ])
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->itemLabel(fn (array $state): string => Guest::find($state['guest_id'] ?? null)?->name ?? 'Новый гость')
                                ->columnSpanFull(),
                        ]),

                    Tab::make('Отзывы')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->schema([
                            Repeater::make('eventTestimonials')
                                ->relationship('eventTestimonials')
                                ->label('')
                                ->addActionLabel('Добавить отзыв')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Select::make('testimonial_id')
                                            ->label('Отзыв')
                                            ->relationship('testimonial', 'author_name')
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                        TextInput::make('sort_order')
                                            ->label('Порядок')
                                            ->numeric()
                                            ->default(0),
                                        Toggle::make('is_visible')
                                            ->label('Показывать на сайте')
                                            ->default(true),
                                    ]),
                                ])
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->itemLabel(fn (array $state): string => Testimonial::find($state['testimonial_id'] ?? null)?->author_name ?? 'Новый отзыв')
                                ->columnSpanFull(),
                        ]),

                    Tab::make('Hero-слайдер')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Repeater::make('heroSlides')
                                ->relationship('heroSlides')
                                ->label('Слайды')
                                ->addActionLabel('Добавить слайд')
                                ->schema([
                                    TextInput::make('title')->label('Заголовок')->maxLength(255),
                                    TextInput::make('subtitle')->label('Подзаголовок')->maxLength(500),
                                    FileUpload::make('image')
                                        ->label('Фоновое изображение (необязательно)')
                                        ->image()
                                        ->disk('public')
                                        ->visibility('public')
                                        ->directory('hero-slides')
                                        ->imagePreviewHeight('200')
                                        ->imageEditor()
                                        ->maxSize(10240), // Максимальный размер 10 МБ
                                    ColorPicker::make('background_color')
                                        ->label('Цвет фона (если нет изображения)')
                                        ->default('#0f172a'),
                                    Grid::make(3)->schema([
                                        TextInput::make('button_text')->label('Текст кнопки'),
                                        TextInput::make('button_url')->label('URL кнопки')->url(),
                                        Toggle::make('is_button_visible')->label('Показывать кнопку')->default(true),
                                    ]),
                                    Toggle::make('is_active')->label('Показывать слайд')->default(true),
                                    TextInput::make('sort_order')->label('Порядок')->numeric()->default(0),
                                ])
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->itemLabel(fn (array $state) => $state['title'] ?? 'Новый слайд')
                                ->columnSpanFull(),
                        ]),

                    Tab::make('Расписание')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            Repeater::make('days')
                                ->relationship('days')
                                ->label('Дни мероприятия')
                                ->addActionLabel('Добавить день')
                                ->schema([
                                    Grid::make(4)->schema([
                                        DatePicker::make('date')->label('Дата')->native(false),
                                        TextInput::make('label')->label('Название дня'),
                                        TextInput::make('sort_order')->label('Порядок')->numeric()->default(0),
                                        Toggle::make('is_active')->label('Активен')->default(true),
                                    ]),
                                    Textarea::make('description')->label('Описание дня')->rows(2),
                                    Repeater::make('events')
                                        ->relationship('events')
                                        ->label('События дня')
                                        ->addActionLabel('Добавить событие')
                                        ->schema([
                                            Grid::make(4)->schema([
                                                TextInput::make('start_time')->label('Начало')->mask('99:99')->placeholder('09:00')->rules(['regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'])->dehydrateStateUsing(fn ($state) => $state ? (strlen($state) === 4 ? substr($state, 0, 2) . ':' . substr($state, 2) : $state) : null),
                                                TextInput::make('end_time')->label('Конец')->mask('99:99')->placeholder('10:00')->rules(['regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'])->dehydrateStateUsing(fn ($state) => $state ? (strlen($state) === 4 ? substr($state, 0, 2) . ':' . substr($state, 2) : $state) : null),
                                                FileUpload::make('icon_image')
                                                    ->label('Иконка (фото)')
                                                    ->image()
                                                    ->disk('public')
                                                    ->visibility('public')
                                                    ->directory('events/schedule-icons')
                                                    ->imagePreviewHeight('100')
                                                    ->imageEditor(),
                                                ColorPicker::make('color')->label('Цвет точки'),
                                            ]),
                                            TextInput::make('title')->label('Название')->required()->columnSpanFull(),
                                            Textarea::make('description')->label('Описание')->rows(2)->columnSpanFull(),
                                            Grid::make(4)->schema([
                                                Select::make('speaker_id')->label('Спикер')->relationship('speaker', 'name')->searchable()->preload(),
                                                TextInput::make('location')->label('Место')->maxLength(255),
                                                Toggle::make('is_break')->label('Перерыв'),
                                                TextInput::make('sort_order')->label('Порядок')->numeric()->default(0),
                                            ]),
                                        ])
                                        ->orderColumn('sort_order')
                                        ->collapsible()
                                        ->itemLabel(fn (array $state) => ($state['start_time'] ?? '--:--') . ' ' . ($state['title'] ?? 'Новое событие'))
                                        ->columnSpanFull(),
                                ])
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->itemLabel(fn (array $state) => ($state['date'] ?? '??') . ' - ' . ($state['label'] ?? 'Новый день'))
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
