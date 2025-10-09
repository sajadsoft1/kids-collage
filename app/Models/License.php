<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasTranslationAuto;
use App\Traits\HasView;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $title
 * @property string $description
 */
class License extends Model implements HasMedia
{
    use HasFactory;
    use HasSeoOption;
    use HasSlugFromTranslation;
    use HasTranslationAuto;
    use HasView;
    use InteractsWithMedia;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'slug',
        'view_count',
        'languages',
        'published',
    ];

    protected $casts = [
        'languages' => 'array',
        'published' => BooleanEnum::class,
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
        return localized_route('license.detail', ['license' => $this->slug]);
    }
}
