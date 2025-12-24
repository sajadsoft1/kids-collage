<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Enums\SeoRobotsMetaEnum;
use App\Helpers\Utils;
use App\Models\Blog;
use App\Models\Bulletin;
use App\Models\Category;
use App\Models\CourseTemplate;
use App\Models\Event;
use App\Models\Page;
use App\Models\PortFolio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Dynamic SEO Service
 *
 * Handles SEO data management, validation, and persistence
 * for the DynamicSeo Livewire component.
 */
class DynamicSeoService
{
    protected Model $model;

    protected SeoChartService $chartService;

    protected SeoScoreService $scoreService;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->chartService = new SeoChartService($model);
        $this->scoreService = new SeoScoreService($model);
    }

    /** Create a service instance from class name and ID. */
    public static function make(string $class, int $id): self
    {
        $model = Utils::getEloquent($class)::with('seoOption')->find($id);

        return new self($model);
    }

    /** Get the model instance. */
    public function getModel(): Model
    {
        return $this->model;
    }

    /** Get the chart service instance. */
    public function charts(): SeoChartService
    {
        return $this->chartService;
    }

    /** Get the score service instance. */
    public function scores(): SeoScoreService
    {
        return $this->scoreService;
    }

    /** Ensure SEO option exists for the model. */
    public function ensureSeoOptionExists(): void
    {
        if ( ! $this->model->seoOption) {
            $this->model->seoOption()->create([
                'title' => $this->model->title ?? '',
                'description' => $this->model->description ?? '',
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ]);
            $this->model->load('seoOption');
        }
    }

    /**
     * Get SEO option data for form initialization.
     *
     * @return array<string, mixed>
     */
    public function getSeoFormData(): array
    {
        $seoOption = $this->model->seoOption;

        return [
            'slug' => $this->model->slug,
            'seo_title' => $seoOption?->title ?? '',
            'seo_description' => $seoOption?->description ?? '',
            'canonical' => $seoOption?->canonical ?? '',
            'old_url' => $seoOption?->old_url ?? '',
            'redirect_to' => $seoOption?->redirect_to ?? '',
            'robots_meta' => $seoOption?->robots_meta?->value ?? SeoRobotsMetaEnum::INDEX_FOLLOW->value,
            'og_image' => $seoOption?->og_image ?? '',
            'twitter_image' => $seoOption?->twitter_image ?? '',
            'focus_keyword' => $seoOption?->focus_keyword ?? '',
            'meta_keywords' => $seoOption?->meta_keywords ?? '',
            'author' => $seoOption?->author ?? '',
            'sitemap_exclude' => $seoOption?->sitemap_exclude ?? false,
            'sitemap_priority' => $seoOption?->sitemap_priority ? (string) $seoOption->sitemap_priority : null,
            'sitemap_changefreq' => $seoOption?->sitemap_changefreq ?? null,
            'image_alt' => $seoOption?->image_alt ?? '',
            'image_title' => $seoOption?->image_title ?? '',
        ];
    }

    /**
     * Update SEO data.
     *
     * @param array<string, mixed> $payload Validated form data
     */
    public function update(array $payload): void
    {
        $seoData = [
            'title' => $payload['seo_title'],
            'description' => $payload['seo_description'],
            'canonical' => $payload['canonical'],
            'old_url' => $payload['old_url'],
            'redirect_to' => $payload['redirect_to'],
            'robots_meta' => SeoRobotsMetaEnum::from($payload['robots_meta']),
            'og_image' => $payload['og_image'] ?? null,
            'twitter_image' => $payload['twitter_image'] ?? null,
            'focus_keyword' => $payload['focus_keyword'] ?? null,
            'meta_keywords' => $payload['meta_keywords'] ?? null,
            'author' => $payload['author'] ?? null,
            'sitemap_exclude' => $payload['sitemap_exclude'] ?? false,
            'sitemap_priority' => $payload['sitemap_priority'] ?? null,
            'sitemap_changefreq' => $payload['sitemap_changefreq'] ?? null,
            'image_alt' => $payload['image_alt'] ?? null,
            'image_title' => $payload['image_title'] ?? null,
        ];

        if ( ! $this->model->seoOption) {
            $this->model->seoOption()->create($seoData);
        } else {
            $this->model->seoOption->update($seoData);
        }

        $this->model->update([
            'slug' => $payload['slug'],
        ]);

        $this->clearSeoCache();
    }

    /**
     * Get validation rules for SEO form.
     *
     * @param string $class Model class name
     *
     * @return array<string, array>
     */
    public function getValidationRules(string $class): array
    {
        return [
            'slug' => [
                'required',
                'max:255',
                'unique:' . Str::plural($class) . ',slug,' . $this->model->id,
                function ($attribute, $value, $fail) use ($class) {
                    if ($this->isSlugExistsInOtherModels($value, $class)) {
                        $fail(trans('validation.unique', ['attribute' => trans('validation.attributes.slug')]));
                    }
                },
            ],
            'seo_title' => ['required', 'string', 'min:10', 'max:60'],
            'seo_description' => ['required', 'string', 'min:50', 'max:160'],
            'canonical' => ['nullable', 'max:255', 'url'],
            'old_url' => ['nullable', 'max:255', 'url'],
            'redirect_to' => ['nullable', 'max:255', 'url'],
            'robots_meta' => ['required', 'in:' . implode(',', SeoRobotsMetaEnum::values())],
            'og_image' => ['nullable', 'max:255', 'url'],
            'twitter_image' => ['nullable', 'max:255', 'url'],
            'focus_keyword' => ['nullable', 'string', 'max:100'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'author' => ['nullable', 'string', 'max:255'],
            'sitemap_exclude' => ['nullable', 'boolean'],
            'sitemap_priority' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'sitemap_changefreq' => ['nullable', 'string', 'in:always,hourly,daily,weekly,monthly,yearly,never'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'image_title' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get back route based on model class.
     *
     * @param string $class Model class name
     */
    public function getBackRoute(string $class): string
    {
        return match ($class) {
            'courseSessionTemplate' => route('admin.course-session-template.index', ['courseTemplate' => $this->model->course_template_id]),
            default => route('admin.' . Str::kebab($class) . '.index'),
        };
    }

    /**
     * Get breadcrumbs data.
     *
     * @param string $backRoute Back route URL
     * @param string $class     Model class name
     *
     * @return array<array{link?: string, icon?: string, label?: string}>
     */
    public function getBreadcrumbs(string $backRoute, string $class): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['link' => $backRoute, 'label' => trans('general.page.index.title', ['model' => trans($class . '.model')])],
            ['label' => $this->model->title],
        ];
    }

    /**
     * Get breadcrumb actions.
     *
     * @param string $backRoute Back route URL
     *
     * @return array<array{link: string, icon: string}>
     */
    public function getBreadcrumbActions(string $backRoute): array
    {
        return [
            ['link' => $backRoute, 'icon' => 's-arrow-left'],
        ];
    }

    /**
     * Check if slug exists in other models with seoOption.
     *
     * @param string $slug  Slug to check
     * @param string $class Current model class name
     */
    protected function isSlugExistsInOtherModels(string $slug, string $class): bool
    {
        $modelsWithSeo = [
            'Blog' => Blog::class,
            'Event' => Event::class,
            'Bulletin' => Bulletin::class,
            'CourseTemplate' => CourseTemplate::class,
            'PortFolio' => PortFolio::class,
            'Page' => Page::class,
            'Category' => Category::class,
        ];

        foreach ($modelsWithSeo as $modelClass => $fullClass) {
            if ($modelClass === $class) {
                continue;
            }

            if (method_exists($fullClass, 'where')) {
                $exists = $fullClass::where('slug', $slug)->where('id', '!=', $this->model->id)->exists();
                if ($exists) {
                    return true;
                }
            }
        }

        return false;
    }

    /** Clear SEO-related cache after update. */
    protected function clearSeoCache(): void
    {
        $cacheKeys = [
            attributeCacheKey($this->model, 'seo_title'),
            attributeCacheKey($this->model, 'seo_description'),
            attributeCacheKey($this->model, 'seo_canonical'),
            attributeCacheKey($this->model, 'seo_redirect_to'),
            attributeCacheKey($this->model, 'seo_robot_meta'),
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Also clear chart cache
        $this->chartService->clearCache();
    }
}
