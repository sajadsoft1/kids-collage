<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasScheduledPublishing
{
    /** Scope to get items that are scheduled to be published */
    public function scopeScheduledForPublishing(Builder $query): Builder
    {
        return $query->where('published', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /** Scope to get items that are published (only if not already defined) */
    public function scopePublishedScheduled(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /** Scope to get items that are not published (only if not already defined) */
    public function scopeUnpublishedScheduled(Builder $query): Builder
    {
        return $query->where('published', false);
    }

    /** Check if the item is scheduled for publishing */
    public function isScheduledForPublishing(): bool
    {
        return ! $this->published &&
               $this->published_at &&
               $this->published_at->isPast();
    }

    /** Check if the item is published */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /** Check if the item is unpublished */
    public function isUnpublished(): bool
    {
        return ! $this->published;
    }

    /** Get the time until publishing (if scheduled) */
    public function getTimeUntilPublishing(): ?Carbon
    {
        if ( ! $this->published_at || $this->published_at->isPast()) {
            return null;
        }

        return $this->published_at;
    }

    /** Check if the item should be published now */
    public function shouldBePublishedNow(): bool
    {
        return $this->isScheduledForPublishing();
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('published', false);
    }
}
