<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Facades\SmartCache;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasCategory;
use App\Traits\HasComment;
use App\Traits\HasModelCache;
use App\Traits\HasScheduledPublishing;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasStatusBoolean;
use App\Traits\HasTranslationAuto;
use App\Traits\HasView;
use App\Traits\HasWishList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

/**
 * @property string $title
 * @property string $description
 */
class Event extends Model implements HasMedia
{
    use CLogsActivity;
    use HasCategory;
    use HasComment;
    use HasFactory;
    use HasModelCache;
    use HasScheduledPublishing;
    use HasSeoOption;
    use HasSlugFromTranslation;
    use HasStatusBoolean;
    use HasTags;
    use HasTranslationAuto;
    use HasView;
    use HasWishList;
    use InteractsWithMedia;

    public array $translatable = [
        'title', 'description', 'body',
    ];

    protected $fillable = [
        'slug',
        'category_id',
        'start_date',
        'end_date',
        'location',
        'capacity',
        'price',
        'is_online',
        'published',
        'languages',
        'published_at',
        'view_count',
        'comment_count',
        'wish_count',
        'extra_attributes',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'published_at' => 'datetime',
        'is_online' => BooleanEnum::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'extra_attributes' => 'array',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                $this->addMediaConversion(Constants::RESOLUTION_854_480)->fit(Fit::Crop, 854, 480);
                $this->addMediaConversion(Constants::RESOLUTION_1280_720)->fit(Fit::Crop, 1280, 720);
            });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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
    public function path(): string
    {
        return localized_route('event.detail', ['event' => $this->slug]);
    }

    public static function latestEvents()
    {
        return SmartCache::for(__CLASS__)
            ->key('latest_events')
            ->remember(function () {
                return self::where('published', BooleanEnum::ENABLE->value)
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();
            }, 3600);
    }
}
