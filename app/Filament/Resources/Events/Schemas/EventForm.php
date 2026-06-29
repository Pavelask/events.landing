<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Models\Event;
use App\Models\Faq;
use App\Models\Guest;
use App\Models\Speaker;
use App\Models\Testimonial;
use App\Services\IconService;
use Filament\Actions\Action;
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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class EventForm
{
    private static array $platformIcons = [
        'facebook' => 'facebook',
        'instagram' => 'instagram',
        'twitter' => 'twitter',
        'linkedin' => 'linkedin',
        'youtube' => 'youtube',
        'telegram' => 'telegram',
        'vk' => 'vk',
        'tiktok' => 'tiktok',
        'rutube' => 'rutube',
        'ok' => 'ok',
        'max' => 'max',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Tabs::make('Мероприятие')
                ->tabs([
                    self::mainTab(),
                    self::mediaTab(),
                    self::participantsTab(),
                    self::contentTab(),
                ])
                ->persistTabInQueryString()
                ->columnSpanFull(),
        ]);
    }

    private static function mainTab(): Tab
    {
        return Tab::make('Основное')
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
                                    ignoreRecord: true,
                                ),
                            DatePicker::make('start_date')
                                ->label('Дата начала')
                                ->required()
                                ->native(false)
                                ->live(onBlur: true)
                                ->closeOnDateSelection()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($state && !$get('end_date')) {
                                        $set('end_date', \Carbon\Carbon::parse($state)->addDay()->toDateString());
                                    }
                                }),
                            DatePicker::make('end_date')
                                ->label('Дата окончания')
                                ->required()
                                ->native(false)
                                ->closeOnDateSelection()
                                ->after('start_date'),
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
                                    'completed' => 'Завершено',
                                    'archived' => 'Архив',
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
                        Textarea::make('yandex_form_url')
                            ->label('HTML код Яндекс Формы')
                            ->placeholder('<iframe src="..." ...></iframe>')
                            ->rows(3)
                            ->visible(fn (callable $get) => $get('registration_type') === 'yandex')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                Section::make('Социальные сети')
                    ->collapsible()
                    ->collapsed()
                    ->headerActions([
                        Action::make('uploadIcon')
                            ->label('Загрузить иконку')
                            ->modalHeading('Загрузить новую иконку')
                            ->form([
                                FileUpload::make('icon_file')
                                    ->label('Файл иконки')
                                    ->image()
                                    ->disk('public')
                                    ->directory('icons')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->required(),
                            ])
                            ->modalWidth('md')
                            ->action(function (array $data) {
                                $iconService = app(IconService::class);
                                $name = pathinfo($data['icon_file'], PATHINFO_FILENAME);
                                $iconService->saveIcon($data['icon_file'], $name);
                            }),
                    ])
                    ->schema([
                        Repeater::make('social_links')
                            ->label('Социальные сети')
                            ->addActionLabel('Добавить соцсеть')
                            ->collapsed()
                            ->defaultItems(0)
                            ->schema([
                                Grid::make(3)->schema([
                                    Select::make('platform')
                                        ->label('Платформа')
                                        ->options([
                                            'facebook' => 'Facebook',
                                            'instagram' => 'Instagram',
                                            'twitter' => 'Twitter / X',
                                            'linkedin' => 'LinkedIn',
                                            'youtube' => 'YouTube',
                                            'telegram' => 'Telegram',
                                            'vk' => 'ВКонтакте',
                                            'tiktok' => 'TikTok',
                                            'rutube' => 'Rutube',
                                            'ok' => 'Одноклассники',
                                            'max' => 'MAX',
                                            'custom' => 'Другое',
                                        ])
                                        ->searchable()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('icon', self::$platformIcons[$state] ?? null)),
                                    TextInput::make('url')
                                        ->label('URL страницы')
                                        ->url()
                                        ->required()
                                        ->placeholder('https://...'),
                                    Select::make('icon')
                                        ->label('Иконка')
                                        ->options(function (?string $state) {
                                            $iconService = app(IconService::class);
                                            $options = $iconService->getAvailableIcons()->mapWithKeys(fn ($icon) => [
                                                $icon['value'] => $icon['label'],
                                            ])->toArray();
                                            if ($state && !isset($options[$state])) {
                                                $options[$state] = ucfirst($state) . ' (текущая)';
                                            }
                                            return $options;
                                        })
                                        ->searchable()
                                        ->nullable()
                                        ->helperText('Из папки storage/app/public/icons')
                                        ->suffix(fn (?string $state): ?string => $state ? ucfirst($state) : null),
                                ]),
                            ])
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->itemLabel(fn (array $state) => ucfirst($state['platform'] ?? 'Соцсеть'))
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
                        RichEditor::make('venue_how_to_get')
                            ->label('Как добраться')
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
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
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Согласие на обработку персональных данных')
                    ->schema([
                        Toggle::make('show_personal_data_consent')
                            ->label('Показывать на сайте')
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),
                        RichEditor::make('personal_data_consent')
                            ->label('Согласие на обработку персональных данных')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('events/privacy')
                            ->extraInputAttributes(['style' => 'min-height: 150px;'])
                            ->visible(fn (callable $get) => $get('show_personal_data_consent'))
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Баннер о cookie')
                    ->schema([
                        Toggle::make('show_cookie_banner')
                            ->label('Показывать баннер на сайте')
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('privacy_cookie_banner_title')
                            ->label('Заголовок баннера')
                            ->placeholder('Политика использования файлов cookie')
                            ->default('Политика использования файлов cookie')
                            ->maxLength(255)
                            ->visible(fn (callable $get) => $get('show_cookie_banner'))
                            ->columnSpanFull(),
                        Textarea::make('privacy_cookie_banner_text')
                            ->label('Текст баннера')
                            ->placeholder('На сайте используются файлы cookie для базовой работы и обеспечения безопасности.')
                            ->rows(3)
                            ->visible(fn (callable $get) => $get('show_cookie_banner'))
                            ->columnSpanFull(),
                        RichEditor::make('privacy_cookie_policy')
                            ->label('Политика использования файлов cookie')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('events/cookie-policy')
                            ->extraInputAttributes(['style' => 'min-height: 150px;'])
                            ->visible(fn (callable $get) => $get('show_cookie_banner'))
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    private static function mediaTab(): Tab
    {
        return Tab::make('Медиа')
            ->icon('heroicon-o-photo')
            ->schema([
                Section::make('Иконка мероприятия')
                    ->schema([
                        \Filament\Schemas\Components\View::make('filament.components.favicon-preview'),
                        \Filament\Actions\Action::make('generateFavicon')
                            ->label('Сгенерировать иконку')
                            ->icon('heroicon-o-arrow-path')
                            ->color('primary')
                            ->requiresConfirmation()
                            ->modalHeading('Генерация иконки')
                            ->modalDescription('Иконка будет сгенерирована из текущего постера. Вы уверены?')
                            ->action(function ($record) {
                                if (!$record || !$record->poster_image) {
                                    return;
                                }
                                $observer = new \App\Observers\EventObserver();
                                $reflection = new \ReflectionMethod($observer, 'generateFavicons');
                                $reflection->setAccessible(true);
                                $reflection->invoke($observer, $record);
                            })
                            ->visible(fn (?Event $record) => $record && !self::hasFavicon($record)),
                    ])
                    ->collapsible()
                    ->collapsed(),

                FileUpload::make('poster_image')
                    ->label('Постер')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('events/posters')
                    ->imagePreviewHeight('200')
                    ->imageEditor()
                    ->live(),
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

                Section::make('Hero-слайдер')
                    ->schema([
                        Repeater::make('heroSlides')
                            ->relationship('heroSlides')
                            ->label('Слайды')
                            ->addActionLabel('Добавить слайд')
                            ->defaultItems(0)
                            ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): ?array => filled($data['title'] ?? null) || filled($data['image'] ?? null) ? $data : null)
                            ->mutateRelationshipDataBeforeSaveUsing(fn (array $data): ?array => filled($data['title'] ?? null) || filled($data['image'] ?? null) ? $data : null)
                            ->schema([
                                TextInput::make('title')->label('Заголовок')->maxLength(255),
                                TextInput::make('subtitle')->label('Подзаголовок')->maxLength(500),
                                FileUpload::make('image')
                                    ->label('Фоновое изображение')
                                    ->image()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('hero-slides')
                                    ->imagePreviewHeight('200')
                                    ->imageEditor()
                                    ->maxSize(10240)
                                    ->required(),
                                Grid::make(3)->schema([
                                    Toggle::make('is_button_visible')->label('Показывать кнопку')->default(false)->live(),
                                    TextInput::make('button_text')->label('Текст кнопки')
                                        ->visible(fn (callable $get) => $get('is_button_visible')),
                                    TextInput::make('button_url')->label('URL кнопки')
                                        ->url()
                                        ->requiredIf('is_button_visible', true)
                                        ->visible(fn (callable $get) => $get('is_button_visible')),
                                ]),
                                Grid::make(3)->schema([
                                    Toggle::make('is_active')->label('Показывать слайд')->default(true),
                                    ColorPicker::make('background_color')
                                        ->label('Цвет фона')
                                        ->default('#0f172a'),
                                    TextInput::make('sort_order')->label('Порядок')->numeric()->default(0),
                                ]),
                            ])
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['title'] ?? 'Новый слайд')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    private static function participantsTab(): Tab
    {
        return Tab::make('Участники')
            ->icon('heroicon-o-users')
            ->schema([
                Section::make('Спикеры')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('eventSpeakers')
                            ->relationship('eventSpeakers')
                            ->label('')
                            ->addActionLabel('Добавить спикера')
                            ->defaultItems(0)
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

                Section::make('Гости')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('eventGuests')
                            ->relationship('eventGuests')
                            ->label('')
                            ->addActionLabel('Добавить гостя')
                            ->defaultItems(0)
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

                Section::make('Отзывы')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('eventTestimonials')
                            ->relationship('eventTestimonials')
                            ->label('')
                            ->addActionLabel('Добавить отзыв')
                            ->defaultItems(0)
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
            ]);
    }

    private static function contentTab(): Tab
    {
        return Tab::make('Контент')
            ->icon('heroicon-o-document-text')
            ->schema([
                Section::make('FAQ')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('eventFaqs')
                            ->relationship('eventFaqs')
                            ->label('Вопросы и ответы')
                            ->addActionLabel('Добавить вопрос')
                            ->defaultItems(0)
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

                Section::make('Документы')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('documents')
                            ->relationship('documents')
                            ->label('Документы')
                            ->addActionLabel('Добавить документ')
                            ->defaultItems(0)
                            ->schema([
                                TextInput::make('id')->hidden(),
                                TextInput::make('title')->label('Название документа')->columnSpanFull(),
                                FileUpload::make('file_path')
                                    ->label('Файл')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('events/documents')
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'application/msword',
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'application/vnd.ms-excel',
                                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                        'application/vnd.ms-powerpoint',
                                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                        'text/plain',
                                        'text/csv',
                                        'application/rtf',
                                        'application/zip',
                                    ])
                                    ->getUploadedFileNameForStorageUsing(fn ($file): string => $file->getClientOriginalName()),
                                TextInput::make('sort_order')->label('Порядок')->numeric()->default(0),
                            ])
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                if (empty($data['title']) && !empty($data['file_path'])) {
                                    $name = pathinfo(basename($data['file_path']), PATHINFO_FILENAME);
                                    $data['title'] = str_replace(['-', '_'], ' ', $name);
                                }
                                return $data;
                            })
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['title'] ?? 'Новый документ')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function hasFavicon($record): bool
    {
        if (!$record || !$record->id) {
            return false;
        }
        return file_exists(storage_path("app/public/events/favicons/{$record->id}-32.png"));
    }

    private static function getFaviconHtml(?Event $record): string
    {
        if (!$record || !$record->id) {
            return '<span class="text-sm text-gray-400 dark:text-gray-500 italic">Иконка не сгенерирована</span>';
        }

        $path32 = storage_path("app/public/events/favicons/{$record->id}-32.png");

        if (!file_exists($path32)) {
            return '<span class="text-sm text-gray-400 dark:text-gray-500 italic">Иконка не сгенерирована</span>';
        }

        $url32 = asset("storage/events/favicons/{$record->id}-32.png");
        $url180 = asset("storage/events/favicons/{$record->id}-180.png");

        return '<div class="flex items-center gap-3">'
            . '<img src="' . $url32 . '" class="rounded border border-gray-200 dark:border-gray-700">'
            . '<img src="' . $url180 . '" class="rounded border border-gray-200 dark:border-gray-700" style="width:64px;height:64px;">'
            . '<div class="text-xs text-gray-500 dark:text-gray-400"><div>32×32 favicon</div><div>180×180 apple-touch-icon</div></div>'
            . '</div>';
    }

}
