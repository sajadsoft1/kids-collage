<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Enums\YesNoEnum;
use App\Traits\CLogsActivity;
use App\Traits\HasStatusBoolean;
use App\Traits\HasTranslationAuto;
use App\Traits\HasUser;
use App\Traits\MorphAttributesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;

///**
// * @property string $title
// * @property string $description
// */
class Comment extends Model
{
    use CLogsActivity,HasStatusBoolean,HasTranslationAuto,HasUser, MorphAttributesTrait;
    use HasFactory;

//    public array $translatable = [
//        'title', 'description',
//    ];

    protected $fillable = [
        'user_id',
        'admin_id',
        'parent_id',
        'morphable_id',
        'morphable_type',
        'published',
        'comment',
        'admin_note',
        'suggest',
        'rate',
        'languages',
        'published_at',
    ];

    protected $casts = [
        'published'    => BooleanEnum::class,
        'suggest'      => YesNoEnum::class,
        'rate'         => 'integer',
        'published_at' => 'date',
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

    /**
     * | Model Relations --------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * | Model Scope ------------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Attributes -------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Custom Methods ---------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
}
