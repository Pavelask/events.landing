<?php

namespace App\Filament\Resources\AnonParticipants\Tables;

use App\Models\AnonParticipant;
use App\Models\Event;
use App\Services\YandexFormsApi;
use Filament\Actions\BulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class AnonParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->limit(5),
                TextColumn::make('event.title')
                    ->label('Мероприятие')
                    ->sortable()
                    ->searchable()
                    ->limit(20),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
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
                    }),
                TextColumn::make('created_at')
                    ->label('Регистрация')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->limit(16),
                TextColumn::make('checked_in_at')
                    ->label('Чек-ин')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->limit(16),
                IconColumn::make('ticket_sent_at')
                    ->label('Билет')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('souvenir_given')
                    ->label('Сув.')
                    ->boolean(),
                IconColumn::make('documentation_given')
                    ->label('Док.')
                    ->boolean(),
                IconColumn::make('clothing_given')
                    ->label('Оде.')
                    ->boolean(),
            ])
            ->contentGrid([
                'md' => 1,
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Мероприятие')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'registered' => 'Зарегистрирован',
                        'arrived' => 'Прибыл',
                        'cancelled' => 'Отменён',
                    ]),
                Filter::make('ticket_sent')
                    ->label('Билет')
                    ->query(function (Builder $query, array $data): Builder {
                        return $data['value']
                            ? $query->whereNotNull('ticket_sent_at')
                            : $query->whereNull('ticket_sent_at');
                    })
                    ->form([
                        Select::make('value')
                            ->options([
                                '1' => 'Отправлен',
                                '0' => 'Не отправлен',
                            ]),
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (AnonParticipant $record): string => \App\Filament\Resources\AnonParticipants\AnonParticipantResource::getUrl('edit', ['record' => $record]))
            ->headerActions([
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

                        $imported = 0;
                        $skipped = 0;

                        foreach ($answers as $answer) {
                            $answerEventId = $answer['answerer']['fields']['event_id'] ?? null;
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
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exporter(\App\Exports\AnonParticipantExport::class),
                BulkAction::make('sendTickets')
                    ->label('Отправить билеты')
                    ->icon('heroicon-o-envelope')
                    ->action(function ($records) {
                        $count = 0;
                        foreach ($records as $record) {
                            if (!$record->ticket_sent_at && $record->event) {
                                $yandexApi = app(YandexFormsApi::class);
                                $formId = $record->event->formTemplate->yandex_form_id ?? null;
                                if (!$formId) {
                                    continue;
                                }
                                $answer = $yandexApi->getAnswer($formId, $record->answer_id);

                                if ($answer) {
                                    $email = $answer['answerer']['email'] ?? null;
                                    if ($email) {
                                        $ticketUrl = route('ticket.show', $record->checkin_token);
                                        Mail::to($email)->send(new \App\Mail\TicketMail($record, $ticketUrl));
                                        $record->update(['ticket_sent_at' => now()]);
                                        $count++;
                                    }
                                }
                            }
                        }
                        Notification::make()
                            ->success()
                            ->title("Отправлено билетов: {$count}")
                            ->send();
                    }),
                BulkAction::make('markArrived')
                    ->label('Отметить прибывших')
                    ->icon('heroicon-o-check-badge')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            if (!$record->checked_in_at) {
                                $record->update([
                                    'checked_in_at' => now(),
                                    'status' => 'arrived',
                                ]);
                            }
                        }
                    }),
                BulkAction::make('exportWithPd')
                    ->label('Экспорт с ПД')
                    ->icon('heroicon-o-document-arrow-down')
                    ->requiresConfirmation()
                    ->modalHeading('Экспорт с персональными данными')
                    ->modalDescription('Файл будет содержать персональные данные участников. Убедитесь, что у вас есть право на обработку этих данных.')
                    ->form([
                        Select::make('event_id')
                            ->label('Мероприятие')
                            ->options(fn () => Event::pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'registered' => 'Зарегистрирован',
                                'arrived' => 'Прибыл',
                                'cancelled' => 'Отменён',
                            ])
                            ->nullable(),
                    ])
                    ->action(function (array $data) {
                        $filters = array_filter($data);
                        \App\Jobs\ExportAnonParticipantsWithPdJob::dispatch($filters, auth()->id());
                        Notification::make()
                            ->title('Задача экспорта запущена')
                            ->body('Файл будет готов в течение нескольких минут. Вы получите уведомление.')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                \Filament\Actions\Action::make('sendTicket')
                    ->label('')
                    ->icon('heroicon-o-envelope')
                    ->iconSize('md')
                    ->color('primary')
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
                            $email = $answer['answerer']['email'] ?? null;
                            if ($email) {
                                $ticketUrl = route('ticket.show', $record->checkin_token);
                                Mail::to($email)->send(new \App\Mail\TicketMail($record, $ticketUrl));
                                $record->update(['ticket_sent_at' => now()]);
                                $label = $record->ticket_sent_at ? 'Билет отправлен повторно' : 'Билет отправлен';
                                Notification::make()->title($label)->success()->send();
                            }
                        }
                    })
                    ->visible(fn (AnonParticipant $record) => !$record->ticket_sent_at),
                \Filament\Actions\Action::make('markArrived')
                    ->label('')
                    ->icon('heroicon-o-check-badge')
                    ->iconSize('md')
                    ->color('success')
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
                \Filament\Actions\Action::make('cancel')
                    ->label('')
                    ->icon('heroicon-o-x-mark')
                    ->iconSize('md')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Отменить регистрацию?')
                    ->action(function (AnonParticipant $record) {
                        $record->update(['status' => 'cancelled']);
                        Notification::make()->title('Регистрация отменена')->success()->send();
                    })
                    ->visible(fn (AnonParticipant $record) => $record->status !== 'cancelled'),
            ]);
    }
}
