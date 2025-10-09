<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Store;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Models\Order;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class GenerateOrderAndItemsPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        // Create the order
        $order = Order::create([
            'order_number'    => 'ORD-' . strtoupper(uniqid('', true)),
            'type'            => OrderTypeEnum::COURSE->value,
            'user_id'         => $dto->getUser()->id,
            'discount_id'     => $dto->getDiscount()?->id,
            'pure_amount'     => $dto->getPureAmount(),
            'discount_amount' => $dto->getDiscountAmount(),
            'total_amount'    => $dto->getTotalAmount(),
            'status'          => $dto->getFromPayload('status',OrderStatusEnum::PENDING->value),
            'note'            => $dto->getFromPayload('note'),
        ]);

        // Create order items
        foreach ($dto->getItems() as $itemData) {
            $order->items()->create([
                'itemable_type' => $itemData['itemable_type'] ?? null,
                'itemable_id'   => $itemData['itemable_id'] ?? null,
                'price'         => $itemData['price'] ?? 0,
                'quantity'      => $itemData['quantity'] ?? 1,
            ]);
        }

        // Create payments
        foreach ($dto->getPayments() as $paymentData) {
            $extraAttributes = [
                'paid_at'          => $paymentData['status'] === PaymentStatusEnum::PAID->value ? now()->toDateTimeString() : null,
                'payment_link'     => null, // TODO: must generate payment link if payment type is online
                'transaction_id'   => null,
                'last_card_digits' => $paymentData['last_card_digits'] ?? null,
                'tracking_code'    => $paymentData['tracking_code'] ?? null,
            ];

            $order->payments()->create([
                'user_id'          => $dto->getUser()->id,
                'amount'           => $paymentData['amount'] ?? 0,
                'scheduled_date'   => $paymentData['scheduled_date'] ?? null,
                'type'             => $paymentData['type'] ?? PaymentTypeEnum::ONLINE->value,
                'status'           => $paymentData['status'] ?? PaymentStatusEnum::PENDING->value,
                'note'             => $paymentData['note'] ?? null,
                'extra_attributes' => $extraAttributes,
            ]);
        }

        // Increment discount usage
        $dto->getDiscount()?->incrementUsage();

        $dto->setOrder($order->refresh()->load(['items', 'payments', 'discount']));

        return $next($dto);
    }
}
