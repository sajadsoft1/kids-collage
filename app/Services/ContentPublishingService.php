<?php

declare(strict_types=1);

namespace App\Services;

use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class ContentPublishingService
{
    /** Get all models that support scheduled publishing */
    private array $publishableModels = [];

    public function __construct()
    {
        $this->publishableModels = $this->discoverPublishableModels();
    }

    /** Discover models that use HasScheduledPublishing trait */
    private function discoverPublishableModels(): array
    {
        $models     = [];
        $modelsPath = app_path('Models');

        if ( ! File::exists($modelsPath)) {
            return [];
        }

        $modelFiles = File::files($modelsPath);

        foreach ($modelFiles as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $className = 'App\\Models\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);

            if ( ! class_exists($className)) {
                continue;
            }

            try {
                $reflection = new ReflectionClass($className);

                // Check if the class uses HasScheduledPublishing trait
                $traits = $reflection->getTraitNames();
                if (in_array('App\\Traits\\HasScheduledPublishing', $traits)) {
                    $models[] = $className;
                }
            } catch (Exception $e) {
                Log::warning("Could not inspect model class: {$className}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $models;
    }

    /** Get all content scheduled for publishing */
    public function getScheduledContent(): Collection
    {
        $allScheduled = collect();

        foreach ($this->publishableModels as $modelClass) {
            $scheduled    = $this->getScheduledForModel($modelClass);
            $allScheduled = $allScheduled->merge($scheduled);
        }

        return $allScheduled;
    }

    /** Get scheduled content for a specific model */
    public function getScheduledForModel(string $modelClass): Collection
    {
        if ( ! class_exists($modelClass)) {
            return collect();
        }

        return $modelClass::scheduledForPublishing()->get();
    }

    /** Publish all scheduled content immediately */
    public function publishAllScheduled(): int
    {
        $totalPublished = 0;

        foreach ($this->publishableModels as $modelClass) {
            $count = $this->publishScheduledForModel($modelClass);
            $totalPublished += $count;
        }

        Log::info("Published {$totalPublished} scheduled content items");

        return $totalPublished;
    }

    /** Publish scheduled content for a specific model */
    public function publishScheduledForModel(string $modelClass): int
    {
        $scheduledItems = $this->getScheduledForModel($modelClass);

        foreach ($scheduledItems as $item) {
            $this->publishItem($item);
        }

        return $scheduledItems->count();
    }

    /** Publish a single content item */
    public function publishItem(Model $item): bool
    {
        try {
            $currentTime = now();

            $item->update([
                'published' => true,
                'published_at' => $currentTime,
            ]);

            Log::info('Content item published', [
                'model' => get_class($item),
                'id' => $item->id,
                'published_at' => $currentTime,
                'timezone' => config('app.timezone'),
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to publish content item', [
                'model' => get_class($item),
                'id' => $item->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /** Schedule content for publishing at a specific time */
    public function scheduleForPublishing(Model $item, DateTime|string $publishAt): bool
    {
        try {
            $item->update([
                'published' => false,
                'published_at' => $publishAt,
            ]);

            Log::info('Content item scheduled for publishing', [
                'model' => get_class($item),
                'id' => $item->id,
                'publish_at' => $publishAt,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to schedule content item', [
                'model' => get_class($item),
                'id' => $item->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /** Get statistics about scheduled content */
    public function getPublishingStats(): array
    {
        $stats = [];

        foreach ($this->publishableModels as $modelClass) {
            $modelName = class_basename($modelClass);

            // Check which scopes are available on the model
            $hasPublishedScope = method_exists($modelClass, 'scopePublished');
            $hasDraftScope     = method_exists($modelClass, 'scopeDraft');

            $stats[$modelName] = [
                'total' => $modelClass::count(),
                'published' => $hasPublishedScope ? $modelClass::published()->count() : 0,
                'unpublished' => $hasDraftScope ? $modelClass::draft()->count() : 0,
                'scheduled' => $modelClass::scheduledForPublishing()->count(),
            ];
        }

        return $stats;
    }
}
