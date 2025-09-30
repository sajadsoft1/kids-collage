<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Facades\SmartCache;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasScheduledPublishing;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $title
 * @property string $description
 */
class Opinion extends Model implements HasMedia
{
    use CLogsActivity, InteractsWithMedia, SoftDeletes;
    use HasFactory;
    use HasScheduledPublishing;
    use HasTranslationAuto;
    public array $translatable = [
        'title', 'description',
    ];
    protected $fillable = [
        'published',
        'published_at',
        'ordering',
        'view_count',
        'company',
        'user_name',
        'comment',
        'languages',
    ];

    protected $casts = [
        'published'    => BooleanEnum::class,
        'published_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'deleted_at'   => 'datetime',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                $this->addMediaConversion(Constants::RESOLUTION_1280_400)->fit(Fit::Crop, 1280, 400);
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

    /** Model Attributes -------------------------------------------------------------------------- */

public static function homeOpinions()
{
    return SmartCache::for(__CLASS__)
        ->key('home_opinions')
        ->remember(function ()  {
            return self::where('published', BooleanEnum::ENABLE->value)
                ->orderBy('ordering', 'asc')
                ->get();
        },3600);
}
}
