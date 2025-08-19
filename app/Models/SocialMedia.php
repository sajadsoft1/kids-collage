<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Enums\SocialMediaPositionEnum;
use App\Helpers\Constants;
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
class SocialMedia extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslationAuto;
    use InteractsWithMedia;

    public array $translatable = [
        'title',
    ];

    protected $fillable = [
        'published', 'link', 'ordering', 'position', 'color',
    ];
    protected $casts = [
        'published'  => BooleanEnum::class,
        'position'   => SocialMediaPositionEnum::class,
        'ordering'   => 'integer',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(
                function () {
                    $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
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

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
}
