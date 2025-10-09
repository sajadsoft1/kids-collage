<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Order;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\Store\CheckAllPaymentsPaidPipe;
use App\Pipelines\OrderCourse\Store\CheckCourseCapacityPipe;
use App\Pipelines\OrderCourse\Store\CheckPaymentsTotalPipe;
use App\Pipelines\OrderCourse\Store\CheckUserNotEnrolledPipe;
use App\Pipelines\OrderCourse\Store\FindUserPipe;
use App\Pipelines\OrderCourse\Store\GenerateEnrollmentPipe;
use App\Pipelines\OrderCourse\Store\GenerateOrderAndItemsPipe;
use App\Pipelines\OrderCourse\Store\SendNotificationsPipe;
use App\Pipelines\OrderCourse\Store\ValidateDiscountPipe;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreOrderCourseAction
{
    use AsAction;

    /**
     * Store a new order with items, payments and optional discount
     *
     * @param array{
     *     user_id: int,
     *     status: string, // see OrderStatusEnum
     *     note?: string,
     *     discount_id?: int,
     *     force?: bool,
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
    public function handle(array $payload): Order
    {
        return DB::transaction(function () use ($payload) {
            $dto = new OrderCourseDTO($payload);

            $result = app(Pipeline::class)
                ->send($dto)
                ->through([
                    FindUserPipe::class,
                    CheckUserNotEnrolledPipe::class,
                    ValidateDiscountPipe::class,
                    CheckPaymentsTotalPipe::class,
                    CheckCourseCapacityPipe::class,
                    GenerateOrderAndItemsPipe::class,
                    CheckAllPaymentsPaidPipe::class,
                    GenerateEnrollmentPipe::class,
                    SendNotificationsPipe::class,
                ])
                ->then(function (OrderCourseDTO $dto) {
                    return $dto;
                });

            return $result->getOrder();
        });
    }
}
