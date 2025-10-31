<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Model;

trait HasSlugFromTranslation
{
    public static function bootHasSlugFromTranslation()
    {
        /** @phpstan-ignore-next-line */
        static::creating(function (Model $model) {
            if (empty($model->slug) && ! empty(request()->input('title'))) {
                $model->slug = $model->uniqueSlug(StringHelper::slug(request()->input('title')));
                if ($model->where('slug', $model->slug)->exists()) {
                    $model->slug .= '-' . time();
                }
            }
        });
    }

    /** Generate a unique slug for the model. */
    public function uniqueSlug(string $slug): string
    {
        $originalSlug = $slug;
        $i            = 1;

        while ($this->where('slug', $slug)->withTrashed()->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        return $slug;
    }
}
