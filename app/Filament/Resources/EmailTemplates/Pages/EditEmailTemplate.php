<?php

namespace App\Filament\Resources\EmailTemplates\Pages;

use App\Filament\Resources\EmailTemplates\EmailTemplateResource;
use App\Mail\TemplateMail;
use App\Models\Event;
use App\Models\Participant;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditEmailTemplate extends EditRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendTest')
                ->label('Отправить тест на email')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->visible(fn (): bool => (bool) auth()->user()?->email)
                ->form([
                    TextInput::make('recipient_email')
                        ->label('Адрес получателя')
                        ->helperText('По умолчанию — ваш email. Можно указать любой (mail.ru, yandex и т.п.), чтобы проверить доставку.')
                        ->email()
                        ->required()
                        ->default(fn (): ?string => auth()->user()?->email),
                ])
                ->action(function (array $data): void {
                    $recipient = new Participant([
                        'name' => auth()->user()?->name ?? 'Администратор',
                        'email' => $data['recipient_email'] ?? auth()->user()?->email,
                    ]);
                    $recipient->id = 0;

                    $event = resolveActiveEvent() ?? Event::make(['title' => 'Мероприятие']);

                    Mail::to($recipient->email)
                        ->send(new TemplateMail($this->record, $event, $recipient));

                    Notification::make()
                        ->success()
                        ->title('Тестовое письмо отправлено')
                        ->body("Проверьте почту: {$recipient->email}")
                        ->send();
                }),
            Action::make('preview')
                ->label('Предпросмотр письма')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn () => route('email-templates.preview', $this->record), shouldOpenInNewTab: true),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveAndCloseFormAction(),
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveAndCloseFormAction(): Action
    {
        return Action::make('saveAndClose')
            ->label('Сохранить и закрыть')
            ->color('primary')
            ->action(function (): void {
                $this->save(shouldRedirect: false);
                $this->redirect(static::getResource()::getUrl('index'));
            });
    }
}
