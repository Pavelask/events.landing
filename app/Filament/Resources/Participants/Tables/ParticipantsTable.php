<?php

namespace App\Filament\Resources\Participants\Tables;

use App\Models\Event;
use App\Models\Participant;
use Filament\Actions\BulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

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
            ->headerActions([
                \Filament\Actions\Action::make('importCsv')
                    ->label('Импорт CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->modalHeading('Импорт участников из CSV')
                    ->modalDescription('Загрузите CSV файл из Яндекс Форм')
                    ->form([
                        Select::make('event_id')
                            ->label('Мероприятие')
                            ->options(fn () => Event::pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(1),
                        FileUpload::make('csv_file')
                            ->label('CSV файл')
                            ->acceptedFileTypes(['text/csv', 'text/plain'])
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        $file = $data['csv_file'];
                        $eventId = $data['event_id'];

                        $path = $file instanceof \Illuminate\Http\UploadedFile
                            ? $file->getRealPath()
                            : storage_path('app/public/' . $file);

                        if (!file_exists($path)) {
                            $path = storage_path('app/' . $file);
                        }

                        if (!file_exists($path)) {
                            Notification::make()->danger('Файл не найден: ' . $file)->send();
                            return;
                        }

                        $handle = fopen($path, 'r');

                        if (!$handle) {
                            Notification::make()->danger('Не удалось открыть файл')->send();
                            return;
                        }

                        $headers = fgetcsv($handle, 0, ',');
                        $imported = 0;
                        $skipped = 0;

                        while (($row = fgetcsv($handle, 0, ',')) !== false) {
                            $row = array_combine($headers, $row);
                            $yandexId = trim($row['ID'] ?? '');
                            $name = trim($row['"Фамилия, Имя, Отчество"'] ?? $row['Фамилия, Имя, Отчество'] ?? '');
                            $phone = trim($row['Номер телефона (мобильный для связи в пути и в г. Сочи)'] ?? '');
                            $email = trim($row['Адрес электронной почты'] ?? '');

                            if (!$yandexId || !$name || in_array($name, ['00000', '0000', '00'])) {
                                $skipped++;
                                continue;
                            }

                            $exists = Participant::where('event_id', $eventId)
                                ->where('answer_id', $yandexId)->exists();

                            if ($exists) {
                                $skipped++;
                                continue;
                            }

                            Participant::create([
                                'event_id' => $eventId,
                                'answer_id' => $yandexId,
                                'name' => $name ?: null,
                                'email' => $email ?: null,
                                'phone' => $phone ?: null,
                                'checkin_token' => Str::random(40),
                                'status' => 'registered',
                            ]);
                            $imported++;
                        }

                        fclose($handle);

                        Notification::make()
                            ->title("Импорт завершён")
                            ->body("Импортировано: {$imported}, Пропущено: {$skipped}")
                            ->success()
                            ->send();
                    }),
            ])
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
                            \Filament\Notifications\Notification::make()->title('Участник отмечен как прибывший')->success()->send();
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
                        \Filament\Notifications\Notification::make()->title('Чек-ин сброшен')->success()->send();
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
