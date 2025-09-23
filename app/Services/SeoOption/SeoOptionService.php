<?php

declare(strict_types=1);

namespace App\Services\SeoOption;

use App\Enums\SeoRobotsMetaEnum;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SeoOptionService
{
    /** Create SEO options for the given model. */
    public function create(Model|Tag $model, array $payload): void
    {
        $data = [
            'title'       => Str::limit(Arr::get($payload, 'title'), 60),
            'description' => Str::limit(Arr::get($payload, 'description'), 160),
            'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW->value,
        ];

        $model->seoOption()->create($data);
    }
}
