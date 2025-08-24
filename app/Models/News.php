<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use App\Traits\HasCategory;
use App\Traits\HasComment;
use App\Traits\HasScheduledPublishing;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasView;
use App\Traits\HasWishList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

/**
 * @property string $title
 * @property string $description
 */
class News extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslationAuto;
    use HasSlugFromTranslation;
    use HasCategory;
    use HasComment;
    use HasScheduledPublishing;
    use HasSeoOption;
    use HasTags;
    use HasView;
    use HasWishList;
    use InteractsWithMedia;

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
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'published_at' => 'datetime',
    ];

    public array $translatable = [
        'title', 'description', 'body'
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */
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

    /**
     * Model Relations --------------------------------------------------------------------------
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */


    /**
     * Model Attributes --------------------------------------------------------------------------
     */


    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
    public function path(): string
    {
        return localized_route('news.detail', ['slug' => $this->slug]);
    }

}
