<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;

trait CrudHelperTrait
{
    public bool $showErrors = false;

    /**
     * Parse timestamp value (handles both Unix timestamps and date strings)
     *
     * @param string|int|null $timestamp
     */
    public function parseTimestamp($timestamp): ?Carbon
    {
        if ( ! $timestamp) {
            return null;
        }

        // Handle Unix timestamp (numeric string or integer)
        if (is_numeric($timestamp)) {
            return Carbon::createFromTimestamp((int) $timestamp);
        }

        // Handle regular date strings
        return Carbon::parse($timestamp);
    }

    /**
     * Set the published at date
     *
     * @param string|int|null $publishedAt
     */
    public function setPublishedAt($publishedAt): ?string
    {
        return $publishedAt ? Carbon::parse($publishedAt)->format('Y-m-d H:i:s') : null;
    }

    /**
     * Normalize the published at date
     *
     * @param string $key
     */
    public function normalizePublishedAt(array $payload, $key='published_at'): array
    {
        $payload[$key] = $payload[$key] ?: null;

        return $payload;
    }

    /** Get the has published at property */
    public function getHasPublishedAtProperty(): bool
    {
        return property_exists($this, 'published_at');
    }
}
