<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

class Utils
{
    public static function getEloquent(string $type): ?string
    {
        $reference = Str::studly($type);
        $model     = 'App\\Models\\' . $reference;

        return match ($type) {
            default => $model,
        };
    }

    public static function getKeyFromEloquent($class): string
    {
        return Str::kebab(last(explode('\\', $class)));
    }
}
