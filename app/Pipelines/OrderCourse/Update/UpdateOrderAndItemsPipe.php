<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Update;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class UpdateOrderAndItemsPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        $order = $dto->getOrder();

        // Update order
        $order->update([
            'discount_id'     => $dto->getDiscount()?->id,
            'pure_amount'     => $dto->getPureAmount(),
            'discount_amount' => $dto->getDiscountAmount(),
            'total_amount'    => $dto->getTotalAmount(),
            'status'          => $dto->getFromPayload('status') ?? $order->status,
            'note'            => $dto->getFromPayload('note') ?? $order->note,
        ]);

        // Update order items if provided
        if ($dto->getItems()->isNotEmpty()) {
            // Delete old items
            $order->items()->delete();

            // Create new items
            foreach ($dto->getItems() as $itemData) {
                $order->items()->create([
                    'itemable_type' => $itemData['itemable_type'] ?? null,
                    'itemable_id'   => $itemData['itemable_id'] ?? null,
                    'price'         => $itemData['price'] ?? 0,
                    'quantity'      => $itemData['quantity'] ?? 1,
                ]);
            }
        }

        // Update payments if provided
        if ($dto->getPayments()->isNotEmpty()) {
            // Delete old payments
            $order->payments()->delete();

            // Create new payments
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
        }

        $dto->setOrder($order->refresh()->load(['items', 'payments', 'discount']));

        return $next($dto);
    }
}
