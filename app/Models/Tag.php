<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Constants;
use App\Traits\HasSeoOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $body
 * @property string $description
 */
class Tag extends \Spatie\Tags\Tag implements HasMedia
{
    use HasFactory;
    use HasSeoOption;
    use InteractsWithMedia;

    public array $tagTranslatable = [
        'description', 'body',
    ];

    protected $fillable = ['languages', 'name', 'slug', 'order_column', 'type'];

    protected $casts = [
        'languages' => 'array',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function customTranslations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable')
            ->where('locale', app()->getLocale());
    }

    public function translationsPure(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function scopeSearch(Builder $query, $keyword): Builder
    {
        return $query->whereHas('translations', function ($query) use ($keyword) {
            $query->where('value', 'like', '%' . $keyword . '%');
        });
    }

    public function getAttribute($key): mixed
    {
        if ( ! $key) {
            return null;
        }

        // If the attribute exists in the attribute array or has a "get" mutator we will
        // get the attribute's value. Otherwise, we will proceed as if the developers
        // are asking for a relationship's value. This covers both types of values.
        if (array_key_exists($key, $this->attributes) ||
            array_key_exists($key, $this->casts) ||
            $this->hasGetMutator($key) ||
            $this->hasAttributeMutator($key) ||
            $this->isClassCastable($key)) {
            return $this->getAttributeValue($key);
        }

        if (in_array($key, $this->tagTranslatable, true)) {
            $value = cache()->rememberForever(generateCacheKey(__CLASS__, Arr::get($this->attributes, 'id', 0), $key, app()->getLocale()), function () use ($key) {
                return $this->customTranslations()->where('key', $key)->first()->value ?? null;
            });
            if (empty($value)) {
                $value = cache()->rememberForever(generateCacheKey(__CLASS__, Arr::get($this->attributes, 'id', 0), $key, config('app.fallback_locale')), function () use ($key) {
                    return $this->customTranslations()->where('key', $key)->first()->value ?? null;
                });
            }

            return $value;
        }

        return $this->isRelation($key) || $this->relationLoaded($key)
            ? $this->getRelationValue($key)
            : $this->throwMissingAttributeExceptionIfApplicable($key);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::deleted(static function ($model) {
            if ( ! in_array(SoftDeletes::class, class_uses($model), true)) {
                $model->translations()->delete();
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(
                function () {
                    $this->addMediaConversion(Constants::RESOLUTION_512_SQUARE)
                        ->keepOriginalImageFormat()
                        ->fit(Fit::Crop, 512, 512);

                    $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                        ->keepOriginalImageFormat()
                        ->fit(Fit::Crop, 720, 720);
                }
            );
    }

    /**
     * Model Relations --------------------------------------------------------------------------
     */

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public function isInUse(): bool
    {
        return DB::table('taggables')->where('tag_id', $this->id)->exists();
    }
}
