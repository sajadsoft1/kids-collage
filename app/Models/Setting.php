<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SettingEnum;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property mixed $extra_attributes
 */
class Setting extends Model implements HasMedia
{
    use CLogsActivity, HasSchemalessAttributes, InteractsWithMedia;

    protected $fillable = [
        'key',
        'permissions',
        'extra_attributes', // nullable
        'show',
    ];

    protected $casts = [
        'permissions' => 'array',
        'show'        => 'boolean',
    ];

    /**
     * | Model Configuration ----------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)
                    ->fit(Fit::Crop, 100, 100);

                $this->addMediaConversion(Constants::RESOLUTION_512_SQUARE)
                    ->fit(Fit::Crop, 512, 512);

                $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                    ->fit(Fit::Crop, 720, 720);
            });
    }

    /**
     * | Model Relations --------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Scope ------------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Attributes -------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function getTitleAttribute(): string
    {
        return SettingEnum::tryFrom($this->key)->title();
    }
    /**
     * | Model Custom Methods ---------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
}
