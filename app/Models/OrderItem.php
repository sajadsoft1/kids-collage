<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * OrderItem Model
 *
 * Payment link for course enrollments and other purchasable items.
 * Supports polymorphic relationships to link to different types of purchasable entities.
 *
 * @property int                 $id
 * @property int                 $order_id
 * @property string              $itemable_type
 * @property int                 $itemable_id
 * @property float               $price
 * @property int                 $quantity
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Order $order
 * @property-read Model $itemable
 */
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'itemable_type',
        'itemable_id',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /** Get the order that this item belongs to. */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** Get the purchasable item (polymorphic). */
    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Get the total price for this item (price × quantity). */
    public function getTotalPriceAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /** Get the formatted total price. */
    public function getFormattedTotalPriceAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    /** Get the formatted unit price. */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /** Get the item title. */
    public function getItemTitleAttribute(): string
    {
        if ($this->itemable_type === Enrollment::class) {
            return $this->itemable->course->courseTemplate->title ?? 'Course Enrollment';
        }

        if ($this->itemable_type === Course::class) {
            return $this->itemable->courseTemplate->title ?? 'Course';
        }

        return 'Item';
    }

    /** Get the item description. */
    public function getItemDescriptionAttribute(): string
    {
        if ($this->itemable_type === Enrollment::class) {
            $course = $this->itemable->course;
            $template = $course->courseTemplate;
            $term = $course->term;
            
            return "Enrollment in {$template->title} for {$term->title}";
        }

        if ($this->itemable_type === Course::class) {
            $course = $this->itemable;
            $template = $course->courseTemplate;
            $term = $course->term;
            
            return "{$template->title} - {$term->title}";
        }

        return 'Item description';
    }

    /** Get the item type. */
    public function getItemTypeAttribute(): string
    {
        return match ($this->itemable_type) {
            Enrollment::class => 'Course Enrollment',
            Course::class => 'Course',
            default => 'Unknown Item',
        };
    }

    /** Check if this item is for a course enrollment. */
    public function isCourseEnrollment(): bool
    {
        return $this->itemable_type === Enrollment::class;
    }

    /** Check if this item is for a course. */
    public function isCourse(): bool
    {
        return $this->itemable_type === Course::class;
    }

    /** Get the course associated with this item. */
    public function getCourseAttribute(): ?Course
    {
        if ($this->itemable_type === Enrollment::class) {
            return $this->itemable->course;
        }

        if ($this->itemable_type === Course::class) {
            return $this->itemable;
        }

        return null;
    }

    /** Get the enrollment associated with this item. */
    public function getEnrollmentAttribute(): ?Enrollment
    {
        if ($this->itemable_type === Enrollment::class) {
            return $this->itemable;
        }

        return null;
    }

    /** Check if this item can be refunded. */
    public function canBeRefunded(): bool
    {
        // Check if the order allows refunds
        if ( ! $this->order->allowsRefund()) {
            return false;
        }

        // Check if the itemable entity allows refunds
        if ($this->itemable_type === Enrollment::class) {
            return $this->itemable->canBeRefunded();
        }

        if ($this->itemable_type === Course::class) {
            return $this->course->canBeRefunded();
        }

        return false;
    }

    /** Process refund for this item. */
    public function processRefund(?float $amount = null, ?string $reason = null): bool
    {
        if ( ! $this->canBeRefunded()) {
            return false;
        }

        $refundAmount = $amount ?? $this->total_price;

        // Process the refund logic here
        // This would integrate with your payment processor

        return true;
    }

    /** Get the refund policy for this item. */
    public function getRefundPolicyAttribute(): string
    {
        if ($this->isCourseEnrollment()) {
            $course = $this->course;
            
            if ($course->isSelfPaced()) {
                return 'Self-paced courses: 30-day refund policy';
            }

            return 'Instructor-led courses: 7-day refund policy from course start date';
        }

        return 'Standard refund policy applies';
    }

    /** Check if this item is eligible for a discount. */
    public function isEligibleForDiscount(): bool
    {
        if ($this->isCourseEnrollment()) {
            $enrollment = $this->enrollment;
            $course = $enrollment->course;
            
            // Check if course is in early bird period
            if ($course->isEarlyBirdPeriod()) {
                return true;
            }

            // Check if student is eligible for student discount
            if ($enrollment->user->isStudent()) {
                return true;
            }
        }

        return false;
    }

    /** Calculate discount for this item. */
    public function calculateDiscount(float $discountPercent): float
    {
        if ( ! $this->isEligibleForDiscount()) {
            return 0.0;
        }

        return $this->total_price * ($discountPercent / 100);
    }

    /** Get the discounted price. */
    public function getDiscountedPrice(float $discountPercent): float
    {
        $discount = $this->calculateDiscount($discountPercent);

        return $this->total_price - $discount;
    }

    /** Scope for course enrollment items. */
    public function scopeCourseEnrollments($query)
    {
        return $query->where('itemable_type', Enrollment::class);
    }

    /** Scope for course items. */
    public function scopeCourses($query)
    {
        return $query->where('itemable_type', Course::class);
    }

    /** Scope for items by price range. */
    public function scopeByPriceRange($query, float $minPrice, float $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /** Scope for items with minimum quantity. */
    public function scopeWithMinimumQuantity($query, int $quantity)
    {
        return $query->where('quantity', '>=', $quantity);
    }

    /** Scope for items by order. */
    public function scopeByOrder($query, int $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /** Get total sales for a specific period. */
    public static function getTotalSales(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): float
    {
        return static::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        })->sum(DB::raw('price * quantity'));
    }

    /** Get sales statistics. */
    public static function getSalesStatistics(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): array
    {
        $query = static::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        });

        return [
            'total_sales' => $query->sum(DB::raw('price * quantity')),
            'total_items' => $query->sum('quantity'),
            'average_order_value' => $query->avg(DB::raw('price * quantity')),
            'course_enrollments' => $query->where('itemable_type', Enrollment::class)->count(),
            'courses_sold' => $query->where('itemable_type', Course::class)->count(),
        ];
    }
}
