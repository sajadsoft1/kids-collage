<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\StringHelper;
use Closure;

/**
 * @method static creating(Closure $param)
 */
trait HasSlugFromTranslation
{
    protected static function bootHasSlugFromTranslation(): void
    {
        /** @phpstan-ignore-next-line */
        self::creating(function ($model) {
            if (empty($model->slug) && ! empty(request()->input('title'))) {
                $model->slug = StringHelper::slug(request()->input('title'));
                if ($model->where('slug', $model->slug)->exists()) {
                    $model->slug .= '-' . time();
                }
            }
        });
    }
}
