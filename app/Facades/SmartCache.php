<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\SmartCache for(string $class)
 *
 * @see \App\Services\SmartCache
 */
class SmartCache extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'smartcache';
    }
}
