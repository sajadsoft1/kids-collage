<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Store;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class CheckAllPaymentsPaidPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        $order = $dto->getOrder();

        if ( ! $order) {
            return $next($dto);
        }

        // Check if all payments are paid
        $allPaymentsPaid = $order->payments()
            ->whereNotIn('status', [PaymentStatusEnum::PAID->value, PaymentStatusEnum::FAILED->value])
            ->doesntExist();

        if ($allPaymentsPaid && $order->payments()->exists()) {
            $order->update([
                'status' => OrderStatusEnum::COMPLETED->value,
            ]);
        }

        return $next($dto);
    }
}
