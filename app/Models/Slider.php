<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Enums\SliderPositionEnum;
use App\Enums\YesNoEnum;
use App\Facades\SmartCache;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasModelCache;
use App\Traits\HasScheduledPublishing;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasStatusBoolean;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $title
 * @property string $description
 */
class Slider extends Model implements HasMedia
{
    use CLogsActivity,
        HasScheduledPublishing,
        HasSchemalessAttributes,
        HasStatusBoolean,
        InteractsWithMedia;
    use HasFactory;
    use HasModelCache;
    use HasTranslationAuto;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published', 'extra_attributes', 'languages', 'ordering', 'published_at', 'expired_at', 'link', 'position',
        'has_timer', 'timer_start',
    ];

    protected $casts = [
        'published'    => BooleanEnum::class,
        'position'     => SliderPositionEnum::class,
        'has_timer'    => YesNoEnum::class,
        'languages'    => 'array',
        'ordering'     => 'integer',
        'published_at' => 'date',
        'expired_at'   => 'date',
        'timer_start'  => 'date',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                $this->addMediaConversion(Constants::RESOLUTION_1280_400)->fit(Fit::Crop, 1280, 400);
            });
        $this->addMediaCollection('video')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_480_SQUARE)
                    ->width(400)
                    ->height(400);
            });
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function references(): HasMany
    {
        return $this->hasMany(SliderReference::class, 'slider_id');
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(Category::class, 'morphable', 'sliders_reference', 'slider_id', 'morphable_id');
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public static function latestSliders(): Collection
    {
        return SmartCache::for(__CLASS__)
            ->key('latest_sliders')
            ->remember(function () {
                return self::where('published', BooleanEnum::ENABLE->value)
                    ->orderBy('ordering')
                    ->orderBy('id', 'desc')
                    ->get();
            }, 3600);
    }
}
