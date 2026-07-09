<?php

namespace App\Livewire;

use App\Models\AnonParticipant;
use App\Jobs\ExportAnonParticipantsWithPdJob;
use Livewire\Component;

class ExportStatus extends Component
{
    public bool $isExporting = false;

    public ?string $exportFilename = null;

    public ?string $exportError = null;

    public ?string $lastExportTime = null;

    public function mount(): void
    {
        $exportStartedAt = session('export_started_at');
        if ($exportStartedAt && (time() - $exportStartedAt) < 120) {
            $this->isExporting = true;
            $this->lastExportTime = now()->format('H:i:s');
        }

        $this->checkExportStatus();
    }

    public static function canView(): bool
    {
        return true;
    }

    public function startExport(?int $eventId = null): void
    {
        $this->isExporting = true;
        $this->exportFilename = null;
        $this->exportError = null;

        $filters = [];
        if ($eventId) {
            $filters['event_id'] = $eventId;
        }

        ExportAnonParticipantsWithPdJob::dispatch($filters, auth()->id());

        $this->lastExportTime = now()->format('H:i:s');
    }

    public function checkExportStatus(): void
    {
        $exports = glob(storage_path('app/exports/participants_with_pd_*.xlsx'));

        if (!empty($exports)) {
            usort($exports, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });

            $latest = $exports[0];
            $filename = basename($latest);
            $fileTime = filemtime($latest);

            if (time() - $fileTime < 120) {
                $this->isExporting = false;
                $this->exportFilename = $filename;
                return;
            }
        }

        if ($this->isExporting && $this->lastExportTime) {
            $this->dispatch('poll');
        }
    }

    public function render()
    {
        return view('livewire.export-status');
    }
}
