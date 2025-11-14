<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Enums\DiscountTypeEnum;
use App\Helpers\StringHelper;
use App\Traits\CLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;

/**
 * Discount Model
 *
 * Manages discount codes with advanced features including:
 * - Percentage or fixed amount discounts
 * - User-specific or global discounts
 * - Minimum order amount requirements
 * - Usage limits (total and per user)
 * - Date-based activation/expiration
 * - Maximum discount cap for percentage types
 *
 * @property int                 $id
 * @property string              $code
 * @property string              $type
 * @property float               $value
 * @property int|null            $user_id
 * @property float               $min_order_amount
 * @property float|null          $max_discount_amount
 * @property int|null            $usage_limit
 * @property int                 $usage_per_user
 * @property int                 $used_count
 * @property \Carbon\Carbon|null $starts_at
 * @property \Carbon\Carbon|null $expires_at
 * @property bool                $is_active
 * @property string|null         $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<Order> $orders
 */
class Discount extends Model
{
    use CLogsActivity;
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'user_id',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
        'description',
    ];

    protected $casts = [
        'type' => DiscountTypeEnum::class,
        'value' => 'float',
        'min_order_amount' => 'float',
        'max_discount_amount' => 'float',
        'usage_limit' => 'integer',
        'usage_per_user' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => BooleanEnum::class,
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

    /** Get the user this discount is assigned to (if user-specific) */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Get all orders that used this discount */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /** Scope for active discounts */
    public function scopeActive($query)
    {
        return $query->where('is_active', BooleanEnum::ENABLE->value);
    }

    /** Scope for valid discounts (active and within date range) */
    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });
    }

    /** Scope for discounts available to a specific user */
    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id')
                ->orWhere('user_id', $userId);
        });
    }

    /** Scope for discounts by code */
    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }

    /** Scope for percentage discounts */
    public function scopePercentage($query)
    {
        return $query->where('type', DiscountTypeEnum::PERCENTAGE->value);
    }

    /** Scope for fixed amount discounts */
    public function scopeFixedAmount($query)
    {
        return $query->where('type', DiscountTypeEnum::AMOUNT->value);
    }

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /** Check if discount is for a specific user */
    public function isUserSpecific(): bool
    {
        return $this->user_id !== null;
    }

    /** Check if discount is global (for all users) */
    public function isGlobal(): bool
    {
        return $this->user_id === null;
    }

    /** Check if discount is currently valid */
    public function isValid(): bool
    {
        if ( ! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        return ! ($this->expires_at && $this->expires_at->isPast());
    }

    /** Check if discount has reached its usage limit */
    public function hasReachedLimit(): bool
    {
        if ($this->usage_limit === null) {
            return false;
        }

        return $this->used_count >= $this->usage_limit;
    }

    /** Check if discount is expired */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /** Check if discount is percentage type */
    public function isPercentage(): bool
    {
        return $this->type === DiscountTypeEnum::PERCENTAGE;
    }

    /** Check if discount is fixed amount type */
    public function isFixedAmount(): bool
    {
        return $this->type === DiscountTypeEnum::AMOUNT;
    }

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */

    /** Calculate discount amount for a given order amount */
    public function calculateDiscount(float $orderAmount): float
    {
        if ($orderAmount < $this->min_order_amount) {
            return 0;
        }

        $discountAmount = match ($this->type) {
            DiscountTypeEnum::PERCENTAGE => ($orderAmount * $this->value) / 100,
            DiscountTypeEnum::AMOUNT => $this->value,
        };

        // Apply maximum discount cap for percentage type
        if ($this->isPercentage() && $this->max_discount_amount) {
            $discountAmount = min($discountAmount, $this->max_discount_amount);
        }

        // Ensure discount doesn't exceed order amount
        return min($discountAmount, $orderAmount);
    }

    /** Check if discount can be applied to an order */
    public function canBeApplied(float $orderAmount, int $userId): bool
    {
        // Check if discount is valid
        if ( ! $this->isValid()) {
            return false;
        }

        // Check if usage limit reached
        if ($this->hasReachedLimit()) {
            return false;
        }

        // Check minimum order amount
        if ($orderAmount < $this->min_order_amount) {
            return false;
        }

        // Check if user-specific discount matches user
        if ($this->isUserSpecific() && $this->user_id !== $userId) {
            return false;
        }

        // Check user usage limit
        $userUsageCount = $this->orders()
            ->where('user_id', $userId)
            ->count();

        return ! ($userUsageCount >= $this->usage_per_user);
    }

    /** Increment the usage count */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /** Decrement the usage count */
    public function decrementUsage(): void
    {
        if ($this->used_count > 0) {
            $this->decrement('used_count');
        }
    }

    /** Get formatted discount value */
    public function getFormattedValue(): string
    {
        return match ($this->type) {
            DiscountTypeEnum::PERCENTAGE => $this->value . '%',
            DiscountTypeEnum::AMOUNT => StringHelper::toCurrency($this->value),
        };
    }

    /** Get discount status text */
    public function getStatusText(): array
    {
        if ( ! $this->is_active) {
            return [
                'label' => trans('discount.enum.status.inactive'),
                'color' => 'danger',
            ];
        }

        if ($this->hasReachedLimit()) {
            return [
                'label' => trans('discount.enum.status.limit'),
                'color' => 'danger',
            ];
        }

        if ($this->isExpired()) {
            return [
                'label' => trans('discount.enum.status.expired'),
                'color' => 'danger',
            ];
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return [
                'label' => trans('discount.enum.status.furure'),
                'color' => 'secondary',
            ];
        }

        return [
            'label' => trans('discount.enum.status.active'),
            'color' => 'success',
        ];
    }

    /**
     * Validate and apply discount to order amounts
     *
     * @return array{success: bool, message: string, discount_amount: float, final_amount: float}
     */
    public function validateAndCalculate(float $orderAmount, int $userId): array
    {
        if ( ! $this->canBeApplied($orderAmount, $userId)) {
            return [
                'success' => false,
                'message' => $this->getInvalidReason($orderAmount, $userId),
                'discount_amount' => 0,
                'final_amount' => $orderAmount,
            ];
        }

        $discountAmount = $this->calculateDiscount($orderAmount);

        return [
            'success' => true,
            'message' => 'Discount applied successfully',
            'discount_amount' => $discountAmount,
            'final_amount' => $orderAmount - $discountAmount,
        ];
    }

    /** Get reason why discount is invalid */
    private function getInvalidReason(float $orderAmount, int $userId): string
    {
        if ( ! $this->is_active) {
            return 'This discount is currently inactive';
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'This discount is not yet active';
        }

        if ($this->isExpired()) {
            return 'This discount has expired';
        }

        if ($this->hasReachedLimit()) {
            return 'This discount has reached its usage limit';
        }

        if ($orderAmount < $this->min_order_amount) {
            return 'Order amount must be at least $' . number_format($this->min_order_amount, 2);
        }

        if ($this->isUserSpecific() && $this->user_id !== $userId) {
            return 'This discount is not available for your account';
        }

        $userUsageCount = $this->orders()->where('user_id', $userId)->count();
        if ($userUsageCount >= $this->usage_per_user) {
            return 'You have reached the usage limit for this discount';
        }

        return 'This discount cannot be applied';
    }
}
