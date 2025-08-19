<?php

declare(strict_types=1);

use Illuminate\Http\Resources\Json\JsonResource;

if ( ! function_exists('generateCacheKey')) {
    function generateCacheKey(string $type, int $id, string $key, string $local): string
    {
        return 'translated:' . class_basename($type) . ':' . $id . ':' . $key . ':' . $local;
    }
}

if ( ! function_exists('attributeCacheKey')) {
    function attributeCacheKey($model, string $key, ?string $local = null): string
    {
        $local ??= app()->getLocale();
        $updatedDate = $model->updated_at?->timestamp ?? now()->getTimestamp();

        return 'attributes:' . class_basename($model) . ':' . $model->id . ':' . $key . ':' . $updatedDate . ':' . $local;
    }
}

if ( ! function_exists('deleteModelCache')) {
    function deleteModelCache($model): void
    {
        // Ensure the prefix is correctly concatenated
        $pattern = Cache::getPrefix() . 'attributes:' . class_basename($model) . ':' . $model->id . ':*';

        foreach (Cache::connection('cache')->keys($pattern) as $key) { // @phpstan-ignore-line
            Cache::forget(str_replace('laravel_database_', '', $key));
        }
    }
}

if ( ! function_exists('transOrNull')) {
    function transOrNull(string $key): ?string
    {
        // Get the translation using the original `trans` function
        $translation = trans($key);

        // Check if the translation is equal to the original key
        if ($translation === $key) {
            // Translation not found, return null
            return null;
        }

        // Translation found, return the string
        return $translation;
    }
}

if ( ! function_exists('removeTrailingSlash')) {
    function removeTrailingSlash(string $url): string
    {
        // Check if the URL ends with a slash and remove it
        if (str_ends_with($url, '/')) {
            $url = rtrim($url, '/');
        }

        return $url;
    }
}

if ( ! function_exists('getUrl')) {
    function getUrl(): string
    {
        if ($url = config('app.url')) {
            return removeTrailingSlash($url);
        }

        throw new RuntimeException(__('exception.app_url_not_set'));
    }
}

if ( ! function_exists('getResourceOrNull')) {
    function getResourceOrNull($resource): ?JsonResource
    {
        $object = (array) $resource;

        return is_null($object['resource']) ? null : $resource;
    }
}

if ( ! function_exists('isRtl')) {
    function isRtl(...$args): bool
    {
        return in_array(app()->getLocale(), ['fa', 'ar'], true);
    }
}
