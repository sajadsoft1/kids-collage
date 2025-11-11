<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\CLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class NotificationTemplate extends Model
{
    use CLogsActivity;
    use HasFactory;

    protected $fillable = [
        'published',
        'languages',
        'name',
        'channel',
        'message_template',
        'inputs',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'inputs' => 'array',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
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

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
}
