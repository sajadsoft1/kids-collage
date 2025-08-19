<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ContentPublishingService;
use Illuminate\Console\Command;

class PublishScheduledContentCommand extends Command
{
    /** The name and signature of the console command. */
    protected $signature = 'content:publish-scheduled';

    /** The console command description. */
    protected $description = 'Publish all content that is scheduled to be published';

    /** Execute the console command. */
    public function handle(ContentPublishingService $publishingService): int
    {
        $this->info('Starting scheduled content publishing...');

        $totalPublished = $publishingService->publishAllScheduled();

        $this->info("Completed! Total items published: {$totalPublished}");

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
