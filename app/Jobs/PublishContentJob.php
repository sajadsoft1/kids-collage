<?php

declare(strict_types=1);

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class PublishContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** The number of times the job may be attempted. */
    public int $tries = 3;

    /** The number of seconds to wait before retrying the job. */
    public int $backoff = 60;

    /** Create a new job instance. */
    public function __construct(
        private Model $content
    ) {}

    /** Execute the job. */
    public function handle(): void
    {
        try {
            $this->publishContent();

            Log::info('Content published successfully', [
                'model' => get_class($this->content),
                'id' => $this->content->id,
                'published_at' => now()->toDateTimeString(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to publish content', [
                'model' => get_class($this->content),
                'id' => $this->content->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /** Publish the content item */
    private function publishContent(): void
    {
        $this->content->update([
            'published' => true,
            'published_at' => now(),
        ]);
    }

    /** Handle a job failure. */
    public function failed(Throwable $exception): void
    {
        Log::error('Content publishing job failed', [
            'model' => get_class($this->content),
            'id' => $this->content->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
