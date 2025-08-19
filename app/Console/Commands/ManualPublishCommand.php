<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ContentPublishingService;
use Illuminate\Console\Command;

class ManualPublishCommand extends Command
{
    /** The name and signature of the console command. */
    protected $signature = 'content:publish-now {--model= : Specific model to publish}';

    /** The console command description. */
    protected $description = 'Manually publish all scheduled content or content from a specific model';

    /** Execute the console command. */
    public function handle(ContentPublishingService $publishingService): int
    {
        $model = $this->option('model');
        $model = 'App\Models\\' . $model;

        if ($model) {
            $this->info("Publishing scheduled content for model: {$model}");
            $count = $publishingService->publishScheduledForModel($model);
            $this->info("Published {$count} items from {$model}");
        } else {
            $this->info('Publishing all scheduled content...');
            $count = $publishingService->publishAllScheduled();
            $this->info("Published {$count} total items");
        }

        // Show statistics
        $stats = $publishingService->getPublishingStats();
        $this->table(
            ['Model', 'Total', 'Published', 'Unpublished', 'Scheduled'],
            collect($stats)->map(fn ($stat, $model) => [
                $model,
                $stat['total'],
                $stat['published'],
                $stat['unpublished'],
                $stat['scheduled'],
            ])->toArray()
        );

        return Command::SUCCESS;
    }
}
