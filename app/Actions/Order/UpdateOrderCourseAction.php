<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Order;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\Store\CheckAllPaymentsPaidPipe;
use App\Pipelines\OrderCourse\Store\CheckPaymentsTotalPipe;
use App\Pipelines\OrderCourse\Update\FindOrderPipe;
use App\Pipelines\OrderCourse\Update\SendNotificationsPipe;
use App\Pipelines\OrderCourse\Update\UpdateOrderAndItemsPipe;
use App\Pipelines\OrderCourse\Update\ValidateDiscountAndUpdatePipe;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateOrderCourseAction
{
    use AsAction;

    /**
     * Update an existing order with items, payments, and optional discount
     *
     * @param array{
     *     order_id: int,
     *     status?: string, // see OrderStatusEnum
     *     note?: string,
     *     discount_id?: int,
     *     items?: array{
     *         itemable_type: string,
     *         itemable_id: int,
     *         price: float,
     *         quantity: int,
     *     }[],
     *     payments?: array{
     *         amount: float,
     *         scheduled_date: string,
     *         type: string, // see PaymentTypeEnum
     *         status: string, // see PaymentStatusEnum
     *         note?: string,
     *         payment_link?: string,
     *         transaction_id?: string|int,
     *         last_card_digits?: string,
     *         tracking_code?: string,
     *     }[],
     * } $payload
     * @throws Throwable
     */
    public function handle(Order $order, array $payload): Order
    {
        return DB::transaction(function () use ($order, $payload) {
            $payload['order_id'] = $order->id;
            $dto = new OrderCourseDTO($payload);

            $result = app(Pipeline::class)
                ->send($dto)
                ->through([
                    FindOrderPipe::class,
                    ValidateDiscountAndUpdatePipe::class,
                    CheckPaymentsTotalPipe::class,
                    UpdateOrderAndItemsPipe::class,
                    CheckAllPaymentsPaidPipe::class,
                    SendNotificationsPipe::class,
                ])
                ->then(function (OrderCourseDTO $dto) {
                    return $dto;
                });

            return $result->getOrder();
        });
    }
}
