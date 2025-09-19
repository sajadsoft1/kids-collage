<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\HasPublishedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @property string $title
 * @property string $description
 */
class Classroom extends Model
{
    use HasFactory;
    use HasPublishedScope;
    use LogsActivity;
    use HasTranslationAuto;

    protected $fillable = [
        'branch_id',
        'capacity',
        'published',
        'languages',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array'
    ];

    public array $translatable = [
        'title', 'description'
    ];

    /** Model Configuration  -------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('system')
            ->dontSubmitEmptyLogs();
    }

    /** Model Relations  -------------------------------------------------------------------------- */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /** Model Scope  -------------------------------------------------------------------------- */


    /** Model Attributes  -------------------------------------------------------------------------- */


    /** Model Custom Methods  -------------------------------------------------------------------------- */

}
