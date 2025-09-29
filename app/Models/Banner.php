<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BannerSizeEnum;
use App\Enums\BooleanEnum;
use App\Facades\SmartCache;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasModelCache;
use App\Traits\HasScheduledPublishing;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $title
 * @property string $description
 */
class Banner extends Model implements HasMedia
{
    use CLogsActivity, HasFactory, HasScheduledPublishing, HasTranslationAuto, InteractsWithMedia;
    use HasModelCache;
    public array $translatable = [
        'title', 'description',
    ];
    protected $fillable = [
        'published',
        'size',
        'click',
        'link',
        'published_at',
        'languages',
    ];

    protected $casts = [
        'published'    => BooleanEnum::class,
        'size'         => BannerSizeEnum::class,
        'published_at' => 'date',
        'languages'    => 'array',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')->registerMediaConversions(function () {
                if ($this->size === BannerSizeEnum::S1X1) {
                    $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                    $this->addMediaConversion(Constants::RESOLUTION_480_SQUARE)->fit(Fit::Crop, 480, 480);
                } elseif ($this->size === BannerSizeEnum::S16X9) {
                    $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                    $this->addMediaConversion(Constants::RESOLUTION_854_480)->fit(Fit::Crop, 854, 480);
                } elseif ($this->size === BannerSizeEnum::S4X3) {
                    $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                    $this->addMediaConversion(Constants::RESOLUTION_800_600)->fit(Fit::Crop, 800, 600);
                }
            });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getPrimaryResolution(): string
    {
        if ($this->size === BannerSizeEnum::S1X1) {
            return Constants::RESOLUTION_480_SQUARE;
        } elseif ($this->size === BannerSizeEnum::S16X9) {
            return Constants::RESOLUTION_854_480;
        } else {
            return Constants::RESOLUTION_800_600;
        }
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

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
    public function latestBanner()
    {
        return SmartCache::for(__CLASS__)
            ->key('latest_banners')
            ->remember(function ()  {
                return self::where('published', BooleanEnum::ENABLE->value)
                    ->orderBy('id', 'desc')
                    ->get();
            },3600);
    }
}
