<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Traits\CLogsActivity;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;

/**
 * Order Model
 *
 * Manages customer orders with support for:
 * - Multiple order items (polymorphic)
 * - Multiple payments
 * - Discount codes
 * - Activity logging
 *
 * @property int                 $id
 * @property int                 $user_id
 * @property int|null            $discount_id
 * @property float               $pure_amount
 * @property float               $discount_amount
 * @property float               $total_amount
 * @property string              $status
 * @property string|null         $note
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read User                                                   $user
 * @property-read Discount|null                                          $discount
 * @property-read \Illuminate\Database\Eloquent\Collection<OrderItem>   $items
 * @property-read \Illuminate\Database\Eloquent\Collection<Payment>     $payments
 */
class Order extends Model
{
    use CLogsActivity;
    use HasFactory;
    use HasUser;

    protected $fillable = [
        'order_number',
        'user_id',
        'discount_id',
        'type',
        'pure_amount',
        'discount_amount',
        'total_amount',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
        'type' => OrderTypeEnum::class,
        'pure_amount' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
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

    /** Get the discount applied to this order */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /** Get all items in this order */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Get all payments for this order */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /** Scope for orders with specific status */
    public function scopeWithStatus($query, OrderStatusEnum $status)
    {
        return $query->where('status', $status->value);
    }

    /** Scope for pending orders */
    public function scopePending($query)
    {
        return $query->where('status', OrderStatusEnum::PENDING->value);
    }

    /** Scope for processing orders */
    public function scopeProcessing($query)
    {
        return $query->where('status', OrderStatusEnum::PROCESSING->value);
    }

    /** Scope for completed orders */
    public function scopeCompleted($query)
    {
        return $query->where('status', OrderStatusEnum::COMPLETED->value);
    }

    /** Scope for cancelled orders */
    public function scopeCancelled($query)
    {
        return $query->where('status', OrderStatusEnum::CANCELLED->value);
    }

    /** Scope for orders with discount */
    public function scopeWithDiscount($query)
    {
        return $query->whereNotNull('discount_id');
    }

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /** Check if order has a discount applied */
    public function hasDiscount(): bool
    {
        return $this->discount_id !== null;
    }

    /** Get the amount saved through discount */
    public function getSavingsAmount(): float
    {
        return $this->discount_amount;
    }

    /** Get formatted pure amount */
    public function getFormattedPureAmount(): string
    {
        return '$' . number_format($this->pure_amount, 2);
    }

    /** Get formatted discount amount */
    public function getFormattedDiscountAmount(): string
    {
        return '$' . number_format($this->discount_amount, 2);
    }

    /** Get formatted total amount */
    public function getFormattedTotalAmount(): string
    {
        return '$' . number_format($this->total_amount, 2);
    }

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */

    /** Calculate and update order amounts */
    public function recalculateAmounts(): void
    {
        // Calculate pure amount from order items
        $this->pure_amount = $this->items->sum(fn ($item) => $item->price * $item->quantity);

        // Apply discount if exists
        if ($this->discount_id && $this->discount) {
            $discountResult = $this->discount->validateAndCalculate($this->pure_amount, $this->user_id);

            if ($discountResult['success']) {
                $this->discount_amount = $discountResult['discount_amount'];
                $this->total_amount    = $discountResult['final_amount'];
            } else {
                // Discount not valid, remove it
                $this->discount_id     = null;
                $this->discount_amount = 0;
                $this->total_amount    = $this->pure_amount;
            }
        } else {
            $this->discount_amount = 0;
            $this->total_amount    = $this->pure_amount;
        }

        $this->save();
    }

    /**
     * Apply discount to order
     *
     * @return array{success: bool, message: string}
     */
    public function applyDiscount(Discount $discount): array
    {
        $result = $discount->validateAndCalculate($this->pure_amount, $this->user_id);

        if ($result['success']) {
            $this->discount_id     = $discount->id;
            $this->discount_amount = $result['discount_amount'];
            $this->total_amount    = $result['final_amount'];
            $this->save();

            return [
                'success' => true,
                'message' => 'Discount applied successfully',
            ];
        }

        return [
            'success' => false,
            'message' => $result['message'],
        ];
    }

    /** Remove discount from order */
    public function removeDiscount(): void
    {
        $this->discount_id     = null;
        $this->discount_amount = 0;
        $this->total_amount    = $this->pure_amount;
        $this->save();
    }

    /** Get total paid amount */
    public function getTotalPaid(): float
    {
        return $this->payments()->sum('amount');
    }

    /** Get remaining balance */
    public function getRemainingBalance(): float
    {
        return max(0, $this->total_amount - $this->getTotalPaid());
    }

    /** Check if order is fully paid */
    public function isFullyPaid(): bool
    {
        return $this->getTotalPaid() >= $this->total_amount;
    }

    /** Check if order is partially paid */
    public function isPartiallyPaid(): bool
    {
        $paid = $this->getTotalPaid();

        return $paid > 0 && $paid < $this->total_amount;
    }
}
