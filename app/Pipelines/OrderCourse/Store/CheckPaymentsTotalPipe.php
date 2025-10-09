<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Store;

use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class CheckPaymentsTotalPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        abort_if(
            $dto->getPayments()->isEmpty(),
            400,
            trans('order.exceptions.payments_required')
        );

        $paymentsTotal = $dto->getPayments()->sum('amount');
        $totalAmount   = $dto->getTotalAmount();

        abort_if(
            abs($paymentsTotal - $totalAmount) > 0.01,
            400,
            trans('order.exceptions.payments_total_mismatch', [
                'expected' => number_format($totalAmount, 2),
                'received' => number_format($paymentsTotal, 2),
            ])
        );

        return $next($dto);
    }
}
