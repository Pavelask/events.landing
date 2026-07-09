<?php

namespace App\Filament\Resources\AnonParticipants\Tables;

use App\Models\AnonParticipant;
use App\Models\Event;
use App\Services\YandexFormsApi;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class AnonParticipantsTable
{
    protected static function getCachedAnswerData(AnonParticipant $record): ?array
    {
        $cacheKey = "anon_answer_{$record->answer_id}";

        return Cache::remember($cacheKey, 600, function () use ($record) {
            $yandexApi = app(YandexFormsApi::class);
            $formId = $record->event->formTemplate->yandex_form_id ?? null;

            if (!$formId || !$record->answer_id) {
                return null;
            }

            return $yandexApi->getAnswer($formId, $record->answer_id);
        });
    }

    protected static function extractField(array $answerData, array $labels): ?string
    {
        foreach ($answerData['data'] ?? [] as $item) {
            $label = mb_strtolower($item['label'] ?? '');
            if (in_array($label, $labels)) {
                return $item['value'] ?? null;
            }
        }
        return null;
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->size(TextSize::Small)
                    ->toggleable(),
                TextColumn::make('participant_info')
                    ->label('Участник')
                    ->size(TextSize::Small)
                    ->toggleable()
                    ->state(function (AnonParticipant $record): ?string {
                        $data = static::getCachedAnswerData($record);
                        if (!$data) {
                            return $record->answer_id;
                        }

                        $name = static::extractField($data, ['фио участника', 'фамилия имя отчество', 'имя', 'name']);
                        $email = static::extractField($data, ['почта', 'email', 'электронная почта']);
                        $phone = static::extractField($data, ['телефон', 'phone', 'номер телефона']);

                        $lines = [];
                        if ($name) $lines[] = '<strong>' . e($name) . '</strong>';
                        if ($email) $lines[] = '<span class="text-gray-500">' . e($email) . '</span>';
                        if ($phone) $lines[] = '<span class="text-gray-500">' . e($phone) . '</span>';

                        return !empty($lines) ? implode('<br>', $lines) : $record->answer_id;
                    })
                    ->html(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->size(TextSize::Small)
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'registered' => 'Зарег.',
                        'arrived' => 'Прибыл',
                        'cancelled' => 'Отменён',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'registered' => 'gray',
                        'arrived' => 'green',
                        'cancelled' => 'red',
                        default => 'gray',
                    })
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->size(TextSize::Small)
                    ->toggleable(),
                TextColumn::make('checked_in_at')
                    ->label('Чек-ин')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->size(TextSize::Small)
                    ->toggleable(),
                IconColumn::make('ticket_sent_at')
                    ->label('Билет')
                    ->boolean()
                    ->sortable()
                    ->size(IconSize::Small)
                    ->toggleable(),
                IconColumn::make('souvenir_given')
                    ->label('Сувень')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->size(IconSize::Small)
                    ->toggleable(),
                IconColumn::make('documentation_given')
                    ->label('Докум.')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->size(IconSize::Small)
                    ->toggleable(),
                IconColumn::make('clothing_given')
                    ->label('Одежда')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->size(IconSize::Small)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Мероприятие')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->placeholder('Все'),
                SelectFilter::make('status')
                    ->label('Статус')
                    ->placeholder('Все')
                    ->options([
                        'registered' => 'Зарегистрирован',
                        'arrived' => 'Прибыл',
                        'cancelled' => 'Отменён',
                    ]),
                SelectFilter::make('ticket_sent')
                    ->label('Билет')
                    ->placeholder('Все')
                    ->options([
                        '1' => 'Отправлен',
                        '0' => 'Не отправлен',
                    ])
                    ->query(fn (Builder $query, array $state) => $query
                        ->when($state['value'] === '1', fn (Builder $q) => $q->whereNotNull('ticket_sent_at'))
                        ->when($state['value'] === '0', fn (Builder $q) => $q->whereNull('ticket_sent_at'))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (AnonParticipant $record): string => \App\Filament\Resources\AnonParticipants\AnonParticipantResource::getUrl('edit', ['record' => $record]))
            ->headerActions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\Action::make('exportAll')
                        ->label('Экспорт участников')
                        ->icon('heroicon-o-document-arrow-down')
                        ->requiresConfirmation()
                        ->modalHeading('Экспорт участников')
                        ->modalDescription('Файл будет сформирован в фоне и скачан автоматически')
                        ->form([
                            Select::make('event_id')
                                ->label('Мероприятие')
                                ->options(fn () => Event::pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ])
                        ->action(function (array $data) {
                            $filters = [];
                            if (!empty($data['event_id'])) {
                                $filters['event_id'] = $data['event_id'];
                            }

                            \App\Jobs\ExportAnonParticipantsWithPdJob::dispatch($filters, auth()->id());

                            session(['export_started_at' => now()->timestamp]);

                            Notification::make()
                                ->title('Экспорт запущен')
                                ->body('Формируется файл экспорта...')
                                ->info()
                                ->send();
                        }),
                    \Filament\Actions\Action::make('sendTicketsAll')
                        ->label('Отправить билеты')
                        ->icon('heroicon-o-envelope')
                        ->requiresConfirmation()
                        ->modalHeading('Отправить билеты?')
                        ->modalDescription('Билеты будут отправлены всем участникам без билета')
                        ->form([
                            Select::make('event_id')
                                ->label('Мероприятие')
                                ->options(fn () => Event::pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ])
                        ->action(function (array $data) {
                            $query = AnonParticipant::whereNull('ticket_sent_at')->whereHas('event.formTemplate');
                            if (!empty($data['event_id'])) {
                                $query->where('event_id', $data['event_id']);
                            }
                            $records = $query->get();

                            $count = 0;
                            $errors = [];
                            foreach ($records as $record) {
                                $yandexApi = app(YandexFormsApi::class);
                                $formId = $record->event->formTemplate->yandex_form_id ?? null;
                                if (!$formId) {
                                    $errors[] = "ID #{$record->id}: нет form_id";
                                    continue;
                                }
                                $answer = $yandexApi->getAnswer($formId, $record->answer_id);
                                if ($answer) {
                                    $email = null;
                                    foreach ($answer['data'] ?? [] as $item) {
                                        $label = mb_strtolower($item['label'] ?? '');
                                        if (in_array($label, ['почта', 'email', 'электронная почта'])) {
                                            $email = $item['value'] ?? null;
                                            break;
                                        }
                                    }
                                    if ($email) {
                                        try {
                                            $ticketUrl = route('ticket.show', $record->checkin_token);
                                            Mail::to($email)->send(new \App\Mail\TicketMail($record, $ticketUrl));
                                            $record->update(['ticket_sent_at' => now()]);
                                            $count++;
                                        } catch (\Exception $e) {
                                            $errors[] = "ID #{$record->id}: " . $e->getMessage();
                                        }
                                    } else {
                                        $errors[] = "ID #{$record->id}: нет email";
                                    }
                                } else {
                                    $errors[] = "ID #{$record->id}: ошибка API";
                                }
                            }
                            $msg = "Отправлено билетов: {$count}";
                            if (!empty($errors)) {
                                $msg .= ". Ошибки: " . implode('; ', $errors);
                            }
                            Notification::make()->title($msg)->danger(!empty($errors))->success(empty($errors))->send();
                        }),
                    \Filament\Actions\Action::make('markArrivedAll')
                        ->label('Отметить прибывших')
                        ->icon('heroicon-o-check-badge')
                        ->requiresConfirmation()
                        ->modalHeading('Отметить прибывших?')
                        ->form([
                            Select::make('event_id')
                                ->label('Мероприятие')
                                ->options(fn () => Event::pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ])
                        ->action(function (array $data) {
                            $query = AnonParticipant::whereNull('checked_in_at')->where('status', '!=', 'cancelled');
                            if (!empty($data['event_id'])) {
                                $query->where('event_id', $data['event_id']);
                            }
                            $count = $query->update(['checked_in_at' => now(), 'status' => 'arrived']);
                            Notification::make()->title("Отмечено прибывших: {$count}")->success()->send();
                        }),
                    \Filament\Actions\Action::make('cancelArrivalAll')
                        ->label('Отменить прибытие')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->requiresConfirmation()
                        ->modalHeading('Отменить прибытие?')
                        ->form([
                            Select::make('event_id')
                                ->label('Мероприятие')
                                ->options(fn () => Event::pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ])
                        ->action(function (array $data) {
                            $query = AnonParticipant::whereNotNull('checked_in_at');
                            if (!empty($data['event_id'])) {
                                $query->where('event_id', $data['event_id']);
                            }
                            $count = $query->update(['checked_in_at' => null, 'status' => 'registered']);
                            Notification::make()->title("Отменено прибытие: {$count}")->success()->send();
                        }),
                    \Filament\Actions\Action::make('resetTicketsAll')
                        ->label('Сбросить билеты')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->requiresConfirmation()
                        ->modalHeading('Сбросить билеты?')
                        ->modalDescription('Билеты можно будет отправить повторно')
                        ->form([
                            Select::make('event_id')
                                ->label('Мероприятие')
                                ->options(fn () => Event::pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ])
                        ->action(function (array $data) {
                            $query = AnonParticipant::whereNotNull('ticket_sent_at');
                            if (!empty($data['event_id'])) {
                                $query->where('event_id', $data['event_id']);
                            }
                            $count = $query->update(['ticket_sent_at' => null]);
                            Notification::make()->title("Сброшено билетов: {$count}")->success()->send();
                        }),
                    \Filament\Actions\Action::make('cancelAll')
                        ->label('Отменить регистрацию')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Отменить регистрацию?')
                        ->modalDescription('Участники будут отменены')
                        ->form([
                            Select::make('event_id')
                                ->label('Мероприятие')
                                ->options(fn () => Event::pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ])
                        ->action(function (array $data) {
                            $query = AnonParticipant::where('status', '!=', 'cancelled');
                            if (!empty($data['event_id'])) {
                                $query->where('event_id', $data['event_id']);
                            }
                            $count = $query->update(['status' => 'cancelled']);
                            Notification::make()->title("Отменено регистраций: {$count}")->success()->send();
                        }),
                    \Filament\Actions\Action::make('importFromYandex')
                        ->label('Импорт из Яндекс Формы')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->modalHeading('Импорт ответов из Яндекс Формы')
                        ->form([
                            Select::make('event_id')
                                ->label('Мероприятие')
                                ->options(fn () => Event::pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->action(function (array $data) {
                            $yandexApi = app(YandexFormsApi::class);
                            $event = Event::with('formTemplate')->find($data['event_id']);
                            $formId = $event->formTemplate->yandex_form_id ?? null;

                            if (!$formId) {
                                Notification::make()
                                    ->title('Ошибка')
                                    ->body('Для этого мероприятия не задан шаблон формы или ID формы')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $answers = $yandexApi->getAnswers($formId);

                            if (empty($answers)) {
                                Notification::make()
                                    ->title('Ошибка получения ответов')
                                    ->body('Не удалось получить ответы из Яндекс Формы. Проверьте токен и права доступа к форме. Form ID: ' . $formId)
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $imported = 0;
                            $skipped = 0;

                            foreach ($answers as $answer) {
                                $answerData = $answer['data'] ?? [];
                                $answerEventId = null;

                                if (isset($answerData[0]['value']) && !isset($answerData[0]['label'])) {
                                    $answerEventId = $answerData[0]['value'] ?? null;
                                } else {
                                    foreach ($answerData as $item) {
                                        if (mb_strtolower($item['label'] ?? '') === 'event_id' || mb_strtolower($item['id'] ?? '') === 'event_id') {
                                            $answerEventId = $item['value'] ?? null;
                                            break;
                                        }
                                    }
                                }
                                if ($answerEventId != $data['event_id']) {
                                    $skipped++;
                                    continue;
                                }

                                $answerId = $answer['id'] ?? null;
                                if (!$answerId) {
                                    $skipped++;
                                    continue;
                                }

                                $exists = AnonParticipant::where('answer_id', $answerId)->exists();
                                if ($exists) {
                                    $skipped++;
                                    continue;
                                }

                                AnonParticipant::create([
                                    'event_id' => $data['event_id'],
                                    'answer_id' => $answerId,
                                    'checkin_token' => \Illuminate\Support\Str::random(40),
                                    'status' => 'registered',
                                ]);
                                $imported++;
                            }

                            Notification::make()
                                ->title("Импорт завершён")
                                ->body("Импортировано: {$imported}, Пропущено: {$skipped}")
                                ->success()
                                ->send();
                        }),
                ])->label('Действия')->icon('heroicon-o-bars-3'),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exporter(\App\Exports\AnonParticipantExport::class),
            ])
            ->actions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\Action::make('sendTicket')
                        ->label('Отправить билет')
                        ->icon('heroicon-o-envelope')
                        ->action(function (AnonParticipant $record) {
                            $yandexApi = app(YandexFormsApi::class);
                            $formId = $record->event->formTemplate->yandex_form_id ?? null;
                            if (!$formId) {
                                Notification::make()
                                    ->title('Ошибка')
                                    ->body('Для этого мероприятия не задан шаблон формы')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $answer = $yandexApi->getAnswer($formId, $record->answer_id);

                            if ($answer) {
                                $email = null;
                                foreach ($answer['data'] ?? [] as $item) {
                                    $label = mb_strtolower($item['label'] ?? '');
                                    if (in_array($label, ['почта', 'email', 'электронная почта'])) {
                                        $email = $item['value'] ?? null;
                                        break;
                                    }
                                }
                                if ($email) {
                                    try {
                                        $ticketUrl = route('ticket.show', $record->checkin_token);
                                        Mail::to($email)->send(new \App\Mail\TicketMail($record, $ticketUrl));
                                        $record->update(['ticket_sent_at' => now()]);
                                        Notification::make()->title('Билет отправлен')->success()->send();
                                    } catch (\Exception $e) {
                                        Notification::make()
                                            ->title('Ошибка отправки')
                                            ->body($e->getMessage())
                                            ->danger()
                                            ->send();
                                    }
                                } else {
                                    Notification::make()
                                        ->title('Ошибка: нет email')
                                        ->body('В ответе Яндекс Формы не найден email. Answer ID: ' . $record->answer_id)
                                        ->danger()
                                        ->send();
                                }
                            } else {
                                Notification::make()
                                    ->title('Ошибка API Яндекс Форм')
                                    ->body('Не удалось получить данные ответа. Answer ID: ' . $record->answer_id . ', Form ID: ' . $formId)
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->visible(fn (AnonParticipant $record) => !$record->ticket_sent_at),
                    \Filament\Actions\Action::make('markArrived')
                        ->label('Отметить прибытие')
                        ->icon('heroicon-o-check-badge')
                        ->action(function (AnonParticipant $record) {
                            if (!$record->checked_in_at) {
                                $record->update([
                                    'checked_in_at' => now(),
                                    'status' => 'arrived',
                                ]);
                                Notification::make()->title('Участник отмечен как прибывший')->success()->send();
                            }
                        })
                        ->visible(fn (AnonParticipant $record) => !$record->checked_in_at),
                    \Filament\Actions\Action::make('resetCheckin')
                        ->label('Сбросить чек-ин')
                        ->icon('heroicon-o-arrow-path')
                        ->requiresConfirmation()
                        ->modalHeading('Сбросить чек-ин?')
                        ->modalDescription('Участник сможет пройти чек-ин заново')
                        ->action(function (AnonParticipant $record) {
                            $record->update([
                                'checked_in_at' => null,
                                'status' => 'registered',
                            ]);
                            Notification::make()->title('Чек-ин сброшен')->success()->send();
                        })
                        ->visible(fn (AnonParticipant $record) => (bool) $record->checked_in_at),
                    \Filament\Actions\Action::make('resetTicket')
                        ->label('Сбросить билет')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->requiresConfirmation()
                        ->modalHeading('Сбросить билет?')
                        ->modalDescription('Билет можно будет отправить повторно')
                        ->action(function (AnonParticipant $record) {
                            $record->update(['ticket_sent_at' => null]);
                            Notification::make()->title('Билет сброшен')->success()->send();
                        })
                        ->visible(fn (AnonParticipant $record) => (bool) $record->ticket_sent_at),
                    \Filament\Actions\Action::make('cancel')
                        ->label('Отменить регистрацию')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Отменить регистрацию?')
                        ->action(function (AnonParticipant $record) {
                            $record->update(['status' => 'cancelled']);
                            Notification::make()->title('Регистрация отменена')->success()->send();
                        })
                        ->visible(fn (AnonParticipant $record) => $record->status !== 'cancelled'),
                ])->icon('heroicon-o-ellipsis-vertical'),
            ]);
    }
}
