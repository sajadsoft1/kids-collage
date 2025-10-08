<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Discount;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreOrderAction
{
    use AsAction;

    /**
     * Store a new order with items, payments, and optional discount
     *
     * @param array{
     *     user_id: int,
     *     status: string,
     *     note?: string,
     *     discount_code?: string,
     *     items?: array,
     *     payments?: array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Order
    {
        return DB::transaction(function () use ($payload) {
            // Extract items, payments, and discount code from payload
            $items        = Arr::pull($payload, 'items', []);
            $payments     = Arr::pull($payload, 'payments', []);
            $discountCode = Arr::pull($payload, 'discount_code');

            // Calculate pure amount from items if not already provided
            if ( ! empty($items)) {
                $pureAmount             = collect($items)->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
                $payload['pure_amount'] = $pureAmount;
            } else {
                // Use provided amounts or default to 0
                $pureAmount = $payload['pure_amount'] ?? 0;
            }

            // Initialize discount fields if not already provided
            if ( ! isset($payload['discount_amount'])) {
                $payload['discount_amount'] = 0;
            }

            if ( ! isset($payload['total_amount'])) {
                $payload['total_amount'] = $pureAmount;
            }

            if ( ! isset($payload['discount_id'])) {
                $payload['discount_id'] = null;
            }

            // Apply discount if provided
            if ($discountCode) {
                $discount = Discount::byCode($discountCode)
                    ->valid()
                    ->forUser($payload['user_id'])
                    ->first();

                if ($discount && $discount->canBeApplied($pureAmount, $payload['user_id'])) {
                    $discountResult = $discount->validateAndCalculate($pureAmount, $payload['user_id']);

                    if ($discountResult['success']) {
                        $payload['discount_id']     = $discount->id;
                        $payload['discount_amount'] = $discountResult['discount_amount'];
                        $payload['total_amount']    = $discountResult['final_amount'];
                    }
                }
            }

            // Create the order
            $order = Order::create($payload);

            // Create order items
            if ( ! empty($items)) {
                foreach ($items as $itemData) {
                    $order->items()->create([
                        'itemable_type' => $itemData['itemable_type'] ?? null,
                        'itemable_id'   => $itemData['itemable_id'] ?? null,
                        'price'         => $itemData['price'] ?? 0,
                        'quantity'      => $itemData['quantity'] ?? 1,
                    ]);
                }
            }

            // Create associated payments if provided
            if ( ! empty($payments)) {
                foreach ($payments as $paymentData) {
                    // Remove id if it exists (for new payments)
                    unset($paymentData['id']);

                    // Set payment_type to type
                    if (isset($paymentData['payment_type'])) {
                        $paymentData['type'] = $paymentData['payment_type'];
                        unset($paymentData['payment_type']);
                    }

                    // Create payment
                    $order->payments()->create([
                        'user_id'        => $order->user_id,
                        'amount'         => $paymentData['amount'] ?? 0,
                        'paid_at'        => $paymentData['paid_at'] ?? now(),
                        'type'           => $paymentData['type'] ?? 'online',
                        'status'         => $paymentData['status'] ?? 'pending',
                        'note'           => $paymentData['note'] ?? null,
                        'transaction_id' => $paymentData['transaction_id'] ?? null,
                    ]);
                }
            }

            // Increment discount usage count
            if ($order->discount_id) {
                $order->discount->incrementUsage();
            }

            return $order->refresh()->load(['items', 'payments', 'discount']);
        });
    }
}
