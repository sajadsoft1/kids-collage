<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Traits\CLogsActivity;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use CLogsActivity;
    use HasFactory;
    use HasSchemalessAttributes;
    use HasUser;

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'scheduled_date',
        'type',
        'status',
        'note',
        'extra_attributes',
    ];

    protected $casts = [
        'type'           => PaymentTypeEnum::class,
        'status'         => PaymentStatusEnum::class,
        'amount'         => 'float',
        'scheduled_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /** Model Relations -------------------------------------------------------------------------- */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function installment(): HasMany
    {
        return $this->hasMany(Installment::class);
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
