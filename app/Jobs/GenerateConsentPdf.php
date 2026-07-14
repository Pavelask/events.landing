<?php

namespace App\Jobs;

use App\Models\ConsentGenerationLog;
use App\Models\DocumentTemplate;
use App\Models\Participant;
use App\Services\PdfGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateConsentPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $participantId,
        public int $templateId,
        public ?int $generatedBy = null,
    ) {
        $this->onQueue('consents');
    }

    public function handle(): void
    {
        $participant = Participant::findOrFail($this->participantId);
        $template = DocumentTemplate::findOrFail($this->templateId);

        $participant->update(['consent_status' => 'generating']);

        try {
            $service = app(PdfGeneratorService::class);
            $path = $service->generate($participant, $template);

            $participant->update([
                'consent_status' => 'completed',
                'consent_pdf_path' => $path,
                'consent_generated_at' => now(),
                'consent_error' => null,
            ]);

            ConsentGenerationLog::create([
                'participant_id' => $participant->id,
                'template_id' => $template->id,
                'status' => 'success',
                'generated_by' => $this->generatedBy,
            ]);

            Log::info("PDF generated for participant {$participant->id}, template {$template->id}");
        } catch (\Throwable $e) {
            $participant->update([
                'consent_status' => 'failed',
                'consent_error' => $e->getMessage(),
            ]);

            ConsentGenerationLog::create([
                'participant_id' => $participant->id,
                'template_id' => $template->id,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'generated_by' => $this->generatedBy,
            ]);

            Log::error("PDF generation failed for participant {$participant->id}: {$e->getMessage()}");
        }
    }
}
