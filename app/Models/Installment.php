<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InstallmentMethodEnum;
use App\Enums\InstallmentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $description
 */
class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'amount',
        'due_date',
        'method',
        'status',
        'transaction_id',
    ];

    protected $casts = [
        'amount'   => 'float',
        'due_date' => 'date',
        'method'   => InstallmentMethodEnum::class,
        'status'   => InstallmentStatusEnum::class,
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /** Model Relations -------------------------------------------------------------------------- */
    public function payment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Payment::class);
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
