<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Enums\YesNoEnum;
use App\Traits\CLogsActivity;
use App\Traits\HasScheduledPublishing;
use App\Traits\HasTranslationAuto;
use App\Traits\HasView;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;

/**
 * @property string $title
 * @property string $description
 */
class Faq extends Model
{
    use CLogsActivity;
    use HasFactory;
    use HasScheduledPublishing;
    use HasTranslationAuto;
    use HasView;
    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published',
        'published_at',
        'category_id',
        'favorite',
        'ordering',
        'languages',
        'like_count',
        'view_count',
    ];

    protected $casts = [
        'published'    => BooleanEnum::class,
        'published_at' => 'date',
        'favorite'     => YesNoEnum::class,
        'languages'    => 'array',
        'created_at'   => 'date',
        'updated_at'   => 'date',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
