<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\Constants;
use App\Models\SeoOption;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

trait HasSeoOption
{
    public function seoOption(): MorphOne
    {
        return $this->morphOne(SeoOption::class, 'morphable');
    }

    /**
     * | Morphable Attributes -------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     * These attributes have been used in admin panel
     */
    public function getSeoTitleAttribute()
    {
        return Cache::remember(attributeCacheKey($this, 'seo_title'), Constants::CACHE_TIME_1_DAY, function () {
            return $this->seoOption?->title;
        });
    }

    public function getSeoDescriptionAttribute()
    {
        return Cache::remember(attributeCacheKey($this, 'seo_description'), Constants::CACHE_TIME_1_DAY, function () {
            return $this->seoOption?->description;
        });
    }

    public function getSeoCanonicalAttribute()
    {
        return Cache::remember(attributeCacheKey($this, 'seo_canonical'), Constants::CACHE_TIME_1_DAY, function () {
            return $this->seoOption?->canonical;
        });
    }

    public function getSeoRedirectToAttribute()
    {
        return Cache::remember(attributeCacheKey($this, 'seo_redirect_to'), Constants::CACHE_TIME_1_DAY, function () {
            return $this->seoOption?->redirect_to;
        });
    }

    public function getSeoRobotMetaAttribute()
    {
        return Cache::remember(attributeCacheKey($this, 'seo_robot_meta'), Constants::CACHE_TIME_1_DAY, function () {
            return $this->seoOption?->robots_meta->value;
        });
    }

    protected static function bootHasSeoOption(): void
    {
        static::deleted(static function ($model) {
            if ( ! in_array(SoftDeletes::class, class_uses($model), true)) {
                $model->seoOption()->delete();
            }
        });
    }
}
