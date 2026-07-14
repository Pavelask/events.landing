<?php

namespace App\Jobs;

use App\Models\DocumentTemplate;
use App\Models\Participant;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateConsentsBatch implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;

    public function __construct(
        public int $participantId,
        public int $templateId,
        public ?int $generatedBy = null,
    ) {
        $this->onQueue('consents');
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $dispatch = new GenerateConsentPdf(
            $this->participantId,
            $this->templateId,
            $this->generatedBy,
        );

        $dispatch->handle();
    }
}
