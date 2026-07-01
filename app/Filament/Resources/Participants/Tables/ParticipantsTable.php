<?php

namespace App\Filament\Resources\Participants\Tables;

use App\Models\Event;
use App\Models\Participant;
use Filament\Actions\BulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'registered' => 'Зарегистрирован',
                        'verified' => 'Подтверждён',
                        'arrived' => 'Прибыл',
                        'cancelled' => 'Отменён',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'registered' => 'gray',
                        'verified' => 'blue',
                        'arrived' => 'green',
                        'cancelled' => 'red',
                        default => 'gray',
                    }),
                TextColumn::make('source')
                    ->label('Источник')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('checked_in_at')
                    ->label('Чек-ин')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Мероприятие')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('event.status')
                    ->label('Статус мероприятия')
                    ->options([
                        'published' => 'Активное',
                        'draft' => 'Черновик',
                        'completed' => 'Завершено',
                        'archived' => 'Архив',
                    ]),
                SelectFilter::make('status')
                    ->label('Статус участника')
                    ->options([
                        'registered' => 'Зарегистрирован',
                        'verified' => 'Подтверждён',
                        'arrived' => 'Прибыл',
                        'cancelled' => 'Отменён',
                    ]),
                SelectFilter::make('source')
                    ->label('Источник')
                    ->options([
                        'yandex_form' => 'Яндекс Форма',
                        'manual' => 'Ручной ввод',
                        'import' => 'Импорт',
                    ]),
            ])
            ->groups([
                'event.title',
            ])
            ->defaultGroup('event.title')
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                BulkAction::make('sendTickets')
                    ->label('Отправить билеты')
                    ->icon('heroicon-o-envelope')
                    ->action(function ($records) {
                        $count = 0;
                        foreach ($records as $record) {
                            if ($record->email) {
                                \Illuminate\Support\Facades\Mail::to($record->email)->send(new \App\Mail\TicketMail($record, route('ticket.show', $record->checkin_token)));
                                $record->update(['ticket_sent_at' => now()]);
                                $count++;
                            }
                        }
                        \Filament\Notifications\Notification::make()
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
                ExportBulkAction::make()
                    ->exporter(\App\Exports\ParticipantExport::class),
            ])
            ->actions([
                \Filament\Actions\Action::make('sendTicket')
                    ->label('')
                    ->icon('heroicon-o-envelope')
                    ->iconSize('md')
                    ->color('primary')
                    ->action(function (Participant $record) {
                        if ($record->email) {
                            $isResend = (bool) $record->ticket_sent_at;
                            \Illuminate\Support\Facades\Mail::to($record->email)->send(new \App\Mail\TicketMail($record, route('ticket.show', $record->checkin_token)));
                            $record->update(['ticket_sent_at' => now()]);
                            $label = $isResend ? 'Билет отправлен повторно' : 'Билет отправлен';
                            \Filament\Notifications\Notification::make()->title($label)->success()->send();
                        }
                    })
                    ->visible(fn (Participant $record) => (bool) $record->email),
                \Filament\Actions\Action::make('markArrived')
                    ->label('')
                    ->icon('heroicon-o-check-badge')
                    ->iconSize('md')
                    ->color('success')
                    ->action(function (Participant $record) {
                        if (!$record->checked_in_at) {
                            $record->update([
                                'checked_in_at' => now(),
                                'status' => 'arrived',
                            ]);
                            \Filament\Notifications\Notification::make()->success('Участник отмечен как прибывший')->send();
                        }
                    })
                    ->visible(fn (Participant $record) => !$record->checked_in_at),
                \Filament\Actions\Action::make('resetCheckin')
                    ->label('')
                    ->icon('heroicon-o-arrow-path')
                    ->iconSize('md')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Сбросить чек-ин?')
                    ->modalDescription('Участник сможет пройти чек-ин заново')
                    ->action(function (Participant $record) {
                        $record->update([
                            'checked_in_at' => null,
                            'status' => 'registered',
                        ]);
                        \Filament\Notifications\Notification::make()->success('Чек-ин сброшен')->send();
                    })
                    ->visible(fn (Participant $record) => (bool) $record->checked_in_at),
                \Filament\Actions\EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil')
                    ->iconSize('md')
                    ->color('info'),
            ]);
    }
}
