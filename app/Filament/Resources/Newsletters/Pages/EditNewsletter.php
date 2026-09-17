<?php

namespace App\Filament\Resources\Newsletters\Pages;

use App\Filament\Resources\Newsletters\NewsletterResource;
use App\Jobs\SendNewsletterBatch;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditNewsletter extends EditRecord
{
    protected static string $resource = NewsletterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('launch')
                ->label('Запустить рассылку')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->disabled(fn () => in_array($this->record->status, ['running', 'completed']))
                ->requiresConfirmation()
                ->modalHeading('Запустить рассылку?')
                ->modalDescription(fn () => count($this->record->recipients()).' получателей получат письмо через очередь.')
                ->action(function () {
                    $recipients = $this->record->recipients();

                    if ($recipients->isEmpty()) {
                        Notification::make()
                            ->warning()
                            ->title('Нет получателей')
                            ->body('Измените фильтры или выберите другое мероприятие.')
                            ->send();

                        return;
                    }

                    $this->record->update([
                        'status' => 'running',
                        'total_count' => $recipients->count(),
                        'sent_count' => 0,
                        'failed_count' => 0,
                        'started_at' => now(),
                        'finished_at' => null,
                        'created_by' => auth()->id(),
                    ]);

                    foreach ($recipients->chunk(50) as $chunk) {
                        SendNewsletterBatch::dispatch($this->record, $chunk->pluck('id')->all());
                    }

                    Notification::make()
                        ->success()
                        ->title('Рассылка запущена')
                        ->body("{$recipients->count()} получателей поставлено в очередь.")
                        ->send();
                }),

            Action::make('cancel')
                ->label('Отменить')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->disabled(fn () => in_array($this->record->status, ['completed', 'cancelled']))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'status' => 'cancelled',
                        'finished_at' => now(),
                    ]);

                    Notification::make()
                        ->warning()
                        ->title('Рассылка отменена')
                        ->send();
                }),
        ];
    }
}
