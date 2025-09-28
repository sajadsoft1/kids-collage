<?php

declare(strict_types=1);

namespace App\Traits;

use App\Services\SmartCache;

trait HasModelCache
{
    public static function bootHasModelCache(): void
    {
        static::created(fn ($model) => SmartCache::for($model::class)->flush());
        static::updated(fn ($model) => SmartCache::for($model::class)->flush());
        static::deleted(fn ($model) => SmartCache::for($model::class)->flush());
    }

    protected function cache(): SmartCache
    {
        return SmartCache::for(static::class);
    }

    public function putCache(string $key, mixed $value, $ttl = null): void
    {
        $this->cache()->key($key)->put($value, $ttl);
    }

    public function getCache(string $key, mixed $default = null): mixed
    {
        return $this->cache()->key($key)->get($default);
    }

    public function forgetCache(string $key): void
    {
        $this->cache()->key($key)->forget();
    }

    public function flushCaches(): void
    {
        $this->cache()->flush();
    }
}
