<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

trait HasTranslationAuto
{
    public function translations(): MorphMany
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
        
        if (in_array($key, $this->translatable, true)) {
            $value = cache()->rememberForever(generateCacheKey(__CLASS__, Arr::get($this->attributes, 'id', 0), $key, app()->getLocale()), function () use ($key) {
                return $this->translations()->where('key', $key)->first()->value ?? null;
            });
            if (empty($value)) {
                $value = cache()->rememberForever(generateCacheKey(__CLASS__, Arr::get($this->attributes, 'id', 0), $key, config('app.fallback_locale')), function () use ($key) {
                    return $this->translations()->where('key', $key)->first()->value ?? null;
                });
            }
            
            return $value;
        }
        
        return $this->isRelation($key) || $this->relationLoaded($key)
            ? $this->getRelationValue($key)
            : $this->throwMissingAttributeExceptionIfApplicable($key);
    }
    
    protected static function bootHasTranslationAuto(): void
    {
        static::deleted(static function ($model) {
            if ( ! in_array(SoftDeletes::class, class_uses($model), true)) {
                $model->translations()->delete();
            }
        });
    }
}
