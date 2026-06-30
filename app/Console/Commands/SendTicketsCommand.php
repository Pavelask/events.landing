<?php

namespace App\Console\Commands;

use App\Mail\TicketMail;
use App\Models\Participant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTicketsCommand extends Command
{
    protected $signature = 'tickets:send {event_id?}';
    protected $description = 'Отправляет билеты участникам';

    public function handle(): int
    {
        $eventId = $this->argument('event_id');

        $query = Participant::whereNull('ticket_sent_at')
            ->whereNotNull('email');

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $participants = $query->get();

        if ($participants->isEmpty()) {
            $this->info('Нет участников для отправки билетов.');
            return self::SUCCESS;
        }

        $this->info("Найдено {$participants->count()} участников для отправки билетов.");

        $bar = $this->output->createProgressBar($participants->count());
        $bar->start();

        foreach ($participants as $participant) {
            try {
                $ticketUrl = route('ticket.show', $participant->checkin_token);
                Mail::to($participant->email)->send(new TicketMail($participant, $ticketUrl));
                $participant->update(['ticket_sent_at' => now()]);
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nОшибка отправки для участника {$participant->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Отправка билетов завершена.');

        return self::SUCCESS;
    }
}
