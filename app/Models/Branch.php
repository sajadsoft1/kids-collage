<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use App\Traits\HasPublishedScope;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $title
 * @property string $description
 */
class Branch extends Model implements HasMedia
{
    use HasSchemalessAttributes;
    use HasFactory;
    use HasPublishedScope;
    use HasTranslationAuto;
    use InteractsWithMedia;
    use LogsActivity;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published',
        'address',
        'phone',
        'languages',
        'extra_attributes',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
    ];

    /** Model Configuration  -------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('system')
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(
                function () {
                    $this->addMediaConversion(Constants::RESOLUTION_1280_720)->fit(Fit::Crop, 1280, 720);
                }
            );
    }

    /** Model Relations  -------------------------------------------------------------------------- */

    /** Model Scope  -------------------------------------------------------------------------- */

    /** Model Attributes  -------------------------------------------------------------------------- */

    /** Model Custom Methods  -------------------------------------------------------------------------- */
}
