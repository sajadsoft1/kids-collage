<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Support\Str;

trait StrReplaceTrait
{
    public function string_model_replace(string $model, string $content): string
    {
        return str_replace(
            [
                '{{model}}',
                '{{kmodel}}',
                '{{cmodel}}',
                '{{smodel}}',
                '{{pmodel}}',
            ],
            [
                $model,
                Str::kebab($model),
                Str::camel($model),
                Str::snake($model),
                Str::snake(Str::plural($model)),
            ],
            $content
        );
    }
}
