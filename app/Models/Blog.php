<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasCategory;
use App\Traits\HasComment;
use App\Traits\HasScheduledPublishing;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasStatusBoolean;
use App\Traits\HasTranslationAuto;
use App\Traits\HasUser;
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
class Blog extends Model implements HasMedia
{
    use CLogsActivity;
    use HasCategory;
    use HasComment;
    use HasFactory;
    use HasScheduledPublishing;
    use HasSeoOption;
    use HasSlugFromTranslation;
    use HasStatusBoolean;
    use HasTags;
    use HasTranslationAuto;
    use HasUser;
    use HasView;
    use HasWishList;
    use InteractsWithMedia;

    public array $translatable = [
        'title', 'description', 'body',
    ];

    protected $fillable = [
        'slug',
        'published',
        'published_at',
        'user_id',
        'category_id',
        'view_count',
        'comment_count',
        'wish_count',
        'languages',
    ];

    protected $casts = [
        'published'    => BooleanEnum::class,
        'published_at' => 'datetime',
        'languages'    => 'array',
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
}
