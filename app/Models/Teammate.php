<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $title
 * @property string $description
 */
class Teammate extends Model implements HasMedia
{
    use HasFactory;
    use HasSchemalessAttributes;
    use HasTranslationAuto;
    use InteractsWithMedia;

    public array $translatable = [
        'title', 'description', 'bio',
    ];

    protected $fillable = [
        'published',
        'languages',
        'birthday',
        'extra_attributes',
        'position',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'birthday' => 'date',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)->fit(Fit::Crop, 720, 720);
            });
        //        $this->addMediaCollection('bio_image')
        //            ->registerMediaConversions(function () {
        //                $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)->fit(Fit::Crop, 720, 720);
        //            });
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
}
