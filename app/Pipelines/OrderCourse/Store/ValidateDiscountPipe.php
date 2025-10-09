<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Store;

use App\Models\Discount;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class ValidateDiscountPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        $discountId = $dto->getFromPayload('discount_id');

        // Calculate pure amount from items
        $pureAmount = $dto->getItems()->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
        $dto->setPureAmount($pureAmount);
        $dto->setTotalAmount($pureAmount);
        $dto->setDiscountAmount(0);

        // If no discount provided, continue
        if ( ! $discountId) {
            return $next($dto);
        }

        // Find and validate discount
        $discount = Discount::where('id', $discountId)
            ->valid()
            ->forUser($dto->getUser()->id)
            ->first();

        if ($discount && $discount->canBeApplied($pureAmount, $dto->getUser()->id)) {
            $discountResult = $discount->validateAndCalculate($pureAmount, $dto->getUser()->id);

            if ($discountResult['success']) {
                $dto->setDiscount($discount);
                $dto->setDiscountAmount($discountResult['discount_amount']);
                $dto->setTotalAmount($discountResult['final_amount']);
            }
        }

        return $next($dto);
    }
}
