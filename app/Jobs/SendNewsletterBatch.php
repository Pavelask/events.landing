<?php

namespace App\Jobs;

use App\Mail\TemplateMail;
use App\Models\Newsletter;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletterBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 600;

    public function __construct(
        public Newsletter $newsletter,
        public array $recipientIds,
    ) {}

    public function handle(): void
    {
        $event = $this->newsletter->event()->with('formTemplate')->firstOrFail();
        $template = $this->newsletter->template;

        if (! $event || ! $template) {
            $this->markComplete();

            return;
        }

        $sent = 0;
        $failed = 0;

        foreach ($this->recipientIds as $recipientId) {
            $recipient = Participant::find($recipientId);

            if (! $recipient || empty($recipient->email)) {
                $failed++;

                continue;
            }

            try {
                Mail::to($recipient->email)->send(new TemplateMail($template, $event, $recipient));
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::error('SendNewsletterBatch: send failed', [
                    'newsletter_id' => $this->newsletter->id,
                    'recipient_id' => $recipientId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        DB::table('newsletters')
            ->where('id', $this->newsletter->id)
            ->increment('sent_count', $sent);

        if ($failed > 0) {
            DB::table('newsletters')
                ->where('id', $this->newsletter->id)
                ->increment('failed_count', $failed);
        }

        $this->maybeComplete();
    }

    private function markComplete(): void
    {
        Newsletter::where('id', $this->newsletter->id)->update([
            'status' => 'completed',
            'finished_at' => now(),
        ]);
    }

    private function maybeComplete(): void
    {
        $newsletter = Newsletter::find($this->newsletter->id);

        if (! $newsletter) {
            return;
        }

        if ($newsletter->sent_count + $newsletter->failed_count >= $newsletter->total_count) {
            $newsletter->update([
                'status' => ($newsletter->sent_count >= $newsletter->total_count || $newsletter->sent_count > 0)
                    ? 'completed'
                    : 'failed',
                'finished_at' => now(),
            ]);
        }
    }
}
