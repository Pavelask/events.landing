<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupOldConsents extends Command
{
    protected $signature = 'consents:cleanup {--days=365 : Days after event to keep consents}';

    protected $description = 'Delete old consent PDFs for events that ended more than X days ago';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $participants = Participant::where('consent_pdf_path', '!=', null)
            ->whereHas('event', function ($query) use ($cutoffDate) {
                $query->where('end_date', '<', $cutoffDate);
            })
            ->get();

        $deleted = 0;

        foreach ($participants as $participant) {
            $participant->deleteConsentPdf();
            $deleted++;
        }

        $this->info("Deleted {$deleted} consent PDFs.");
        Log::info("Consent cleanup: deleted {$deleted} PDFs for events before {$cutoffDate->format('Y-m-d')}");

        return Command::SUCCESS;
    }
}
