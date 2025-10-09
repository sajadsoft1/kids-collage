<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Update;

use App\Models\Order;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class FindOrderPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        $orderId = $dto->getFromPayload('order_id');

        abort_if( ! $orderId, 400, trans('order.exceptions.order_id_required'));

        $order = Order::with(['items', 'payments', 'discount', 'user'])->find($orderId);

        abort_if( ! $order, 404, trans('order.exceptions.order_not_found'));

        $dto->setOrder($order);
        $dto->setUser($order->user);

        return $next($dto);
    }
}
