<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Update;

use App\Models\Discount;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class ValidateDiscountAndUpdatePipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        $order = $dto->getOrder();
        $newDiscountId = $dto->getFromPayload('discount_id');
        $oldDiscountId = $order?->discount_id;

        // Calculate a pure amount from items (use new items if provided, otherwise use existing)
        $items = $dto->getItems()->isNotEmpty() ? $dto->getItems() : collect($order?->items->toArray());
        $pureAmount = $items->sum(fn ($item) => (is_array($item) ? ($item['price'] ?? 0) : $item->price) * (is_array($item) ? ($item['quantity'] ?? 1) : $item->quantity));

        $dto->setPureAmount($pureAmount);
        $dto->setTotalAmount($pureAmount);
        $dto->setDiscountAmount(0);

        // Handle discount changes
        if ($oldDiscountId && $oldDiscountId !== $newDiscountId) {
            // Decrement old discount usage
            $oldDiscount = Discount::find($oldDiscountId);
            if ($oldDiscount) {
                $oldDiscount->decrementUsage();
            }
        }

        // Apply new discount if provided
        if ($newDiscountId) {
            $discount = Discount::where('id', $newDiscountId)
                ->valid()
                ->forUser($dto->getUser()->id)
                ->first();

            if ($discount && $discount->canBeApplied($pureAmount, $dto->getUser()->id)) {
                $discountResult = $discount->validateAndCalculate($pureAmount, $dto->getUser()->id);

                if ($discountResult['success']) {
                    $dto->setDiscount($discount);
                    $dto->setDiscountAmount($discountResult['discount_amount']);
                    $dto->setTotalAmount($discountResult['final_amount']);

                    // Increment new discount usage if it is different from old one.
                    if ($newDiscountId !== $oldDiscountId) {
                        $discount->incrementUsage();
                    }
                }
            }
        }

        return $next($dto);
    }
}
