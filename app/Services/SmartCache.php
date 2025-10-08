<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use DateInterval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class SmartCache
 *
 * A fluent builder-style cache service with support for:
 * - Automatic prefixing by model class
 * - Tag-based cache when available (Redis, Memcached)
 * - Fallback registry system for file/database drivers
 *
 * Usage:
 * ```php
 * $default = [];
 * SmartCache::for(User::class)
 *     ->key('features')
 *     ->put($default, now()->addHour());
 *
 * $features = SmartCache::for(User::class)
 *     ->key('features')
 *     ->get([]);
 *
 * SmartCache::for(User::class)->flush();
 * ```
 */
class SmartCache
{
    protected string $prefix;
    protected string $key = '';

    public function __construct(string $modelClass)
    {
        $this->prefix = Str::kebab(class_basename($modelClass)) . ':';
    }

    /**
     * Create builder for a given model class.
     *
     * @param  class-string $modelClass
     * @return static
     */
    public static function for(string $modelClass): self
    {
        return new self($modelClass);
    }

    /**
     * Define the cache key (without prefix).
     *
     * @return $this
     */
    public function key(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /** Store a value in cache. */
    public function put(mixed $value, int|Carbon|DateInterval|null $ttl = null): void
    {
        if ($this->supportsTags()) {
            Cache::tags([$this->prefix])->put($this->key, $value, $ttl);

            return;
        }

        $fullKey = $this->prefix . $this->key;
        Cache::put($fullKey, $value, $ttl);
        $this->registerCacheKey($fullKey);
    }

    /** Retrieve a value from cache. */
    public function get(mixed $default = null): mixed
    {
        if ($this->supportsTags()) {
            return Cache::tags([$this->prefix])->get($this->key, $default);
        }

        return Cache::get($this->prefix . $this->key, $default);
    }

    /** Get or set a value in cache. */
    public function remember(callable $callback, int|Carbon|DateInterval|null $ttl = null): mixed
    {
        $value = $this->get('__CACHE_MISS__');

        if ($value === '__CACHE_MISS__') {
            $value = $callback();
            $this->put($value, $ttl);
        }

        return $value;
    }

    /** Remove a specific key from cache. */
    public function forget(): void
    {
        if ($this->supportsTags()) {
            Cache::tags([$this->prefix])->forget($this->key);

            return;
        }

        $fullKey = $this->prefix . $this->key;
        Cache::forget($fullKey);
        $this->unregisterCacheKey($fullKey);
    }

    /** Flush all cache for this model class. */
    public function flush(): void
    {
        if ($this->supportsTags()) {
            Cache::tags([$this->prefix])->flush();

            return;
        }

        $driver = config('cache.default');

        if ($driver === 'database') {
            DB::table('cache')->where('key', 'like', $this->prefix . '%')->delete();
        } else {
            foreach (Cache::pull($this->prefix . 'keys', []) as $key) {
                Cache::forget($key);
            }
        }
    }

    protected function supportsTags(): bool
    {
        $driver = config('cache.default');

        return in_array($driver, ['redis', 'memcached'], true);
    }

    protected function registerCacheKey(string $fullKey): void
    {
        $keys = Cache::get($this->prefix . 'keys', []);
        if ( ! in_array($fullKey, $keys, true)) {
            $keys[] = $fullKey;
            Cache::forever($this->prefix . 'keys', $keys);
        }
    }

    protected function unregisterCacheKey(string $fullKey): void
    {
        $keys = Cache::get($this->prefix . 'keys', []);
        $keys = array_filter($keys, fn ($k) => $k !== $fullKey);
        Cache::forever($this->prefix . 'keys', $keys);
    }
}
