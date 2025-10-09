<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Facades\SmartCache;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasTranslationAuto;
use App\Traits\HasView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $title
 * @property string $body
 */
class Category extends Model implements HasMedia
{
    use CLogsActivity,
        HasFactory,
        HasSchemalessAttributes,
        HasSeoOption,
        HasSlugFromTranslation,
        HasTranslationAuto,
        HasView,
        InteractsWithMedia,
        SoftDeletes;

    public array $translatable = ['title', 'description', 'body'];

    protected $fillable = [
        'published', 'parent_id', 'slug', 'type', 'languages', 'ordering', 'view_count', 'extra_attributes',
    ];

    protected $casts = [
        'type'       => CategoryTypeEnum::class,
        'published'  => BooleanEnum::class,
        'languages'  => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * | Model Configuration ----------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(
                function () {
                    $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                        ->fit(Fit::Crop, 720, 720);
                }
            );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * | Model Relations --------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function sliders(): MorphMany
    {
        return $this->morphMany(Slider::class, 'morphable');
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class, 'category_id');
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class, 'category_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    /**
     * | Model Scope ------------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('published', BooleanEnum::ENABLE->value);
    }

    /**
     * | Model Attributes -------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Custom Methods ---------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public static function blogCategories()
    {
        return SmartCache::for(__CLASS__)
            ->key('blog_categories')
            ->remember(function () {
                return self::where('type', CategoryTypeEnum::BLOG->value)
                    ->where('published', BooleanEnum::ENABLE->value)
                    ->orderBy('ordering', 'asc')
                    ->get();
            }, 3600);
    }

    public static function bulletinCategories()
    {
        return SmartCache::for(__CLASS__)
            ->key('bulletin_categories')
            ->remember(function () {
                return self::where('type', CategoryTypeEnum::BULLETIN->value)
                    ->where('published', BooleanEnum::ENABLE->value)
                    ->orderBy('ordering', 'asc')
                    ->get();
            }, 3600);
    }

    public static function faqCategories()
    {
        return SmartCache::for(__CLASS__)
            ->key('faq_categories')
            ->remember(function () {
                return self::where('type', CategoryTypeEnum::FAQ->value)
                    ->where('published', BooleanEnum::ENABLE->value)
                    ->orderBy('ordering', 'asc')
                    ->get();
            }, 3600);
    }
}
