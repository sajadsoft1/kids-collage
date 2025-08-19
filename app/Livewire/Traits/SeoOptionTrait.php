<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

use App\Enums\SeoRobotsMetaEnum;
use App\Helpers\StringHelper;

trait SeoOptionTrait
{
    public ?string $slug            = '';
    public ?string $seo_title       = '';
    public ?string $seo_description = '';
    public ?string $canonical       = '';
    public ?string $old_url         = '';
    public ?string $redirect_to     = '';
    public ?string $robots_meta     = SeoRobotsMetaEnum::INDEX_FOLLOW->value;

    public function updatedTitle($value): void
    {
        if ( ! $this->model->id || empty($this->seo_title)) {
            $this->seo_title = $value;
        }
        if ( ! $this->model->id || empty($this->slug)) {
            $this->slug = StringHelper::slug($value);
        }
    }

    public function updatedDescription($value): void
    {
        if ( ! $this->model->id || empty($this->seo_description)) {
            $this->seo_description = $value;
        }
    }

    public function seoOptionRules(): array
    {
        return [
            // seo
            'slug'            => ['required', 'string', 'max:255'],
            'seo_title'       => ['required', 'string', 'max:255'],
            'seo_description' => ['required', 'string', 'max:255'],
            'canonical'       => ['nullable', 'url'],
            'old_url'         => ['nullable', 'url'],
            'redirect_to'     => ['nullable', 'url'],
            'robots_meta'     => ['required', 'string', 'in:' . implode(',', SeoRobotsMetaEnum::values())],
        ];
    }

    public function mountStaticFields(): void
    {
        $this->slug            = $this->model->slug;
        $this->seo_title       = $this->model->seoOption->title ?? '';
        $this->seo_description = $this->model->seoOption->description ?? '';
        $this->canonical       = $this->model->seoOption->canonical ?? '';
        $this->old_url         = $this->model->seoOption->old_url ?? '';
        $this->redirect_to     = $this->model->seoOption->redirect_to ?? '';
        $this->robots_meta     = $this->model->seoOption->robots_meta->value ?? '';
    }
}
