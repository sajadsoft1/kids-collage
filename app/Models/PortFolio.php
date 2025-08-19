<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use App\Traits\HasScheduledPublishing;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

/**
 * @property string $title
 * @property string $description
 */
class PortFolio extends Model implements HasMedia
{
    use HasFactory;
    use HasScheduledPublishing;
    use HasTags;
    use HasTranslationAuto;
    use InteractsWithMedia;

    public array $translatable = [
        'title', 'description', 'body',
    ];

    protected $table = 'portfolios';

    protected $fillable = [
        'published',
        'category_id',
        'creator_id',
        'published_at',
        'view_count',
        'comment_count',
        'like_count',
        'wish_count',
        'execution_date',
        'languages',
    ];

    protected $casts = [
        'published'      => BooleanEnum::class,
        'published_at'   => 'datetime',
        'execution_date' => 'datetime',
        'deleted_at'     => 'datetime',
        'languages'      => 'array',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->registerMediaConversions(
                function () {
                    $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)
                        ->fit(Fit::Crop, 100, 100);

                    $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                        ->fit(Fit::Crop, 720, 720);
                }
            );
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
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
}
