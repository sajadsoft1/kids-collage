<?php

declare(strict_types=1);

namespace App\Services\SeoOption;

use App\Enums\SeoRobotsMetaEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SeoOptionService
{
    /** Update the SEO options for the given model. */
    public function update(Model $model, array $payload): void
    {
        $this->saveSeoOption($model, $payload, 'update');
    }
    
    /** Create SEO options for the given model. */
    public function create(Model $model, array $payload): void
    {
        $this->saveSeoOption($model, $payload, 'create');
    }
    
    /** Save SEO options (create or update) for the given model. */
    private function saveSeoOption(Model $model, array $payload, string $operation): void
    {
        $data = $this->prepareSeoData($payload);
        
        if ($operation === 'update') {
            $model->seoOption()->update($data);
        } elseif ($operation === 'create') {
            $model->seoOption()->create($data);
        }
    }
    
    /** Prepare the SEO data for saving. */
    private function prepareSeoData(array $payload): array
    {
        return [
            'title'       => Arr::get($payload, 'seo_title') ?: Arr::get($payload, 'title'),
            'description' => Arr::get($payload, 'seo_description') ?: Str::limit(Arr::get($payload, 'description'), 188),
            'canonical'   => Arr::get($payload, 'canonical'),
            'old_url'     => Arr::get($payload, 'old_url'),
            'redirect_to' => Arr::get($payload, 'redirect_to'),
            'robots_meta' => Arr::get($payload, 'robots_meta', SeoRobotsMetaEnum::INDEX_FOLLOW),
        ];
    }
}
