# SmartCache & HasModelCache - Developer Guide

---

* [Overview](#overview)
* [Installation](#installation)
* [Usage in Models](#usage-in-models)
* [Usage Anywhere](#usage-anywhere)
* [Facade & Blade](#facade-blade)
* [Builder Pattern](#builder-pattern)
* [Automatic Cache Flushing](#automatic-cache-flushing)
* [API Reference](#api-reference)
* [Examples](#examples)
* [Tips](#tips)

---

<a name="overview"></a>

## Overview

SmartCache is a flexible caching service for Laravel applications with support for:

* Automatic prefixing by model class
* Builder pattern for fluent API
* Tag-based cache drivers (Redis, Memcached)
* Fallback registry system for drivers without tags (Database, File, Array)
* Integration with Eloquent models via `HasModelCache` trait
* Automatic cache invalidation on create, update, delete

---

<a name="installation"></a>

## Installation

1. Create `SmartCache` service in `app/Services/SmartCache.php`.
2. Create `HasModelCache` trait in `app/Traits/HasModelCache.php`.
3. (Optional) Create a Facade in `app/Facades/SmartCache.php` for cleaner syntax.
4. No manual registration in `config/app.php` is needed on Laravel 12+, auto-discovery handles it.

---

<a name="usage-in-models"></a>

## Usage in Models

```php
use App\Traits\HasModelCache;

class User extends Model
{
    use HasModelCache;
}

// Store cache for a user
$user = User::find(1);
$user->putCache('profile', ['name' => 'John'], now()->addHour());

// Retrieve cache
$profile = $user->getCache('profile', []);

// Forget cache
$user->forgetCache('profile');

// Flush all caches for this model
$user->flushCaches();
```

---

<a name="usage-anywhere"></a>

## Usage Anywhere

SmartCache can be used outside models (controllers, jobs, services):

```php
use App\Services\SmartCache;

// Store cache
SmartCache::for(User::class)
    ->key('features')
    ->put(['foo' => 'bar'], now()->addMinutes(30));

// Retrieve cache
$features = SmartCache::for(User::class)
    ->key('features')
    ->get([]);

// Remember with closure
$posts = SmartCache::for(User::class)
    ->key('latest-posts')
    ->remember(fn () => Post::latest()->take(10)->get(), 600);

// Flush all user caches
SmartCache::for(User::class)->flush();
```

---

<a name="facade-blade"></a>

## Facade & Blade

If you create the `SmartCache` Facade, usage is easier and Blade-friendly:

```blade
@php
    $features = SmartCache::for(\App\Models\User::class)
        ->key('features')
        ->get([]);
@endphp

<x-feature-list :items="$features" />
```

Benefits of using Facade:

* No need to manually build instances
* Cleaner syntax in controllers & Blade
* IDE autocomplete support

---

<a name="builder-pattern"></a>

## Builder Pattern

SmartCache uses the Builder pattern:

* `for($class)` → sets model class & prefix
* `key($key)` → sets the cache key
* `put($value, $ttl)` / `get($default)` / `remember($callback, $ttl)` / `forget()` / `flush()`

Example:

```php
SmartCache::for(User::class)
    ->key('features')
    ->put(['foo' => 'bar'], now()->addMinutes(30));

$features = SmartCache::for(User::class)
    ->key('features')
    ->get([]);
```

---

<a name="automatic-cache-flushing"></a>

## Automatic Cache Flushing

`HasModelCache` trait automatically flushes all caches for a model when:

* Created
* Updated
* Deleted

```php
static::created(fn ($model) => SmartCache::for($model::class)->flush());
static::updated(fn ($model) => SmartCache::for($model::class)->flush());
static::deleted(fn ($model) => SmartCache::for($model::class)->flush());
```

---

<a name="api-reference"></a>

## API Reference

### SmartCache Service

| Method                                      | Description                                           |
| ------------------------------------------- | ----------------------------------------------------- |
| `for(string $class)`                        | Create a new builder instance for the model or prefix |
| `key(string $key)`                          | Set the cache key                                     |
| `put(mixed $value, $ttl = null)`            | Store value in cache                                  |
| `get(mixed $default = null)`                | Retrieve value from cache                             |
| `remember(callable $callback, $ttl = null)` | Get value or store closure result                     |
| `forget()`                                  | Remove specific cache key                             |
| `flush()`                                   | Remove all cache entries for prefix                   |

### HasModelCache Trait

| Method                                             | Description                               |
| -------------------------------------------------- | ----------------------------------------- |
| `putCache(string $key, mixed $value, $ttl = null)` | Store cache for this model instance       |
| `getCache(string $key, mixed $default = null)`     | Get cached value for this model instance  |
| `forgetCache(string $key)`                         | Remove cache key for this model instance  |
| `flushCaches()`                                    | Remove all caches for this model instance |

---

<a name="examples"></a>

## Examples

```php
// Model cache
$user->putCache('stats', ['posts' => 10]);
$stats = $user->getCache('stats');

// Anywhere else
SmartCache::for(User::class)->key('stats')->put(['posts' => 10]);
$data = SmartCache::for(User::class)->key('stats')->get();
```

---

<a name="tips"></a>

## Tips

* Use Facade inside Blade for clean templates
* Builder pattern makes API fluent & readable
* Automatic prefixing prevents key collisions
* Works with all cache drivers, fallback included
* Supports tag-based cache for efficient flushing (Redis, Memcached)
