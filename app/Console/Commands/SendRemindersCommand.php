<?php

namespace App\Console\Commands;

use App\Mail\TicketReminderMail;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRemindersCommand extends Command
{
    protected $signature = 'reminders:send {event_id?}';
    protected $description = 'Отправляет напоминания участникам за день до мероприятия';

    public function handle(): int
    {
        $eventId = $this->argument('event_id');

        $query = Participant::with('event')
            ->whereNull('ticket_sent_at')
            ->where('email', '!=', null);

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $tomorrow = Carbon::tomorrow()->startOfDay();
        $dayAfterTomorrow = Carbon::tomorrow()->endOfDay();

        $participants = $query->whereHas('event', function ($q) use ($tomorrow, $dayAfterTomorrow) {
            $q->whereDate('start_date', '>=', $tomorrow)
              ->whereDate('start_date', '<=', $dayAfterTomorrow);
        })->get();

        if ($participants->isEmpty()) {
            $this->info('Нет участников для отправки напоминаний.');
            return self::SUCCESS;
        }

        $this->info("Найдено {$participants->count()} участников для напоминаний.");

        $bar = $this->output->createProgressBar($participants->count());
        $bar->start();

        foreach ($participants as $participant) {
            try {
                $ticketUrl = route('ticket.show', $participant->checkin_token);
                Mail::to($participant->email)->send(new TicketReminderMail($participant, $ticketUrl));
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nОшибка отправки для участника {$participant->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Отправка напоминаний завершена.');

        return self::SUCCESS;
    }
}
