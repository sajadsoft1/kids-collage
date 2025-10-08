<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Discount;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateOrderAction
{
    use AsAction;

    /**
     * Update an existing order with items, payments, and optional discount
     *
     * @param array{
     *     user_id?: int,
     *     status?: string,
     *     note?: string,
     *     discount_code?: string,
     *     items?: array,
     *     payments?: array
     * } $payload
     * @throws Throwable
     */
    public function handle(Order $order, array $payload): Order
    {
        return DB::transaction(function () use ($order, $payload) {
            // Track old discount for usage count
            $oldDiscountId = $order->discount_id;

            // Extract items, payments, and discount code from payload
            $items        = Arr::pull($payload, 'items', []);
            $payments     = Arr::pull($payload, 'payments', []);
            $discountCode = Arr::pull($payload, 'discount_code');

            // Sync order items if provided
            if ( ! empty($items)) {
                $existingItemIds = [];

                foreach ($items as $itemData) {
                    // If item has an id, update it
                    if ( ! empty($itemData['id'])) {
                        $item = $order->items()->find($itemData['id']);
                        if ($item) {
                            $item->update([
                                'itemable_type' => $itemData['itemable_type'] ?? $item->itemable_type,
                                'itemable_id'   => $itemData['itemable_id'] ?? $item->itemable_id,
                                'price'         => $itemData['price'] ?? $item->price,
                                'quantity'      => $itemData['quantity'] ?? $item->quantity,
                            ]);
                            $existingItemIds[] = $item->id;
                        }
                    } else {
                        // Create new item
                        $newItem = $order->items()->create([
                            'itemable_type' => $itemData['itemable_type'] ?? null,
                            'itemable_id'   => $itemData['itemable_id'] ?? null,
                            'price'         => $itemData['price'] ?? 0,
                            'quantity'      => $itemData['quantity'] ?? 1,
                        ]);
                        $existingItemIds[] = $newItem->id;
                    }
                }

                // Delete items that are not in the list anymore
                $order->items()->whereNotIn('id', $existingItemIds)->delete();

                // Recalculate pure amount from items
                $pureAmount             = $order->items()->get()->sum(fn ($item) => $item->price * $item->quantity);
                $payload['pure_amount'] = $pureAmount;
            } else {
                // Keep existing pure amount if no items provided
                $pureAmount = $order->pure_amount;
            }

            // Handle discount
            if (isset($discountCode)) {
                if ($discountCode) {
                    // Apply new discount
                    $discount = Discount::byCode($discountCode)
                        ->valid()
                        ->forUser($order->user_id)
                        ->first();

                    if ($discount && $discount->canBeApplied($pureAmount, $order->user_id)) {
                        $discountResult = $discount->validateAndCalculate($pureAmount, $order->user_id);

                        if ($discountResult['success']) {
                            $payload['discount_id']     = $discount->id;
                            $payload['discount_amount'] = $discountResult['discount_amount'];
                            $payload['total_amount']    = $discountResult['final_amount'];
                        }
                    } else {
                        // Invalid discount, remove it
                        $payload['discount_id']     = null;
                        $payload['discount_amount'] = 0;
                        $payload['total_amount']    = $pureAmount;
                    }
                } else {
                    // Remove discount
                    $payload['discount_id']     = null;
                    $payload['discount_amount'] = 0;
                    $payload['total_amount']    = $pureAmount;
                }
            }

            // Update the order
            $order->update($payload);

            // Sync payments if provided
            if ( ! empty($payments)) {
                $existingPaymentIds = [];

                foreach ($payments as $paymentData) {
                    // Set payment_type to type
                    if (isset($paymentData['payment_type'])) {
                        $paymentData['type'] = $paymentData['payment_type'];
                        unset($paymentData['payment_type']);
                    }

                    // If payment has an id, update it
                    if ( ! empty($paymentData['id'])) {
                        $payment = $order->payments()->find($paymentData['id']);
                        if ($payment) {
                            $payment->update([
                                'amount'         => $paymentData['amount'] ?? $payment->amount,
                                'paid_at'        => $paymentData['paid_at'] ?? $payment->paid_at,
                                'type'           => $paymentData['type'] ?? $payment->type,
                                'status'         => $paymentData['status'] ?? $payment->status,
                                'note'           => $paymentData['note'] ?? $payment->note,
                                'transaction_id' => $paymentData['transaction_id'] ?? $payment->transaction_id,
                            ]);
                            $existingPaymentIds[] = $payment->id;
                        }
                    } else {
                        // Create new payment
                        $newPayment = $order->payments()->create([
                            'user_id'        => $order->user_id,
                            'amount'         => $paymentData['amount'] ?? 0,
                            'paid_at'        => $paymentData['paid_at'] ?? now(),
                            'type'           => $paymentData['type'] ?? 'online',
                            'status'         => $paymentData['status'] ?? 'pending',
                            'note'           => $paymentData['note'] ?? null,
                            'transaction_id' => $paymentData['transaction_id'] ?? null,
                        ]);
                        $existingPaymentIds[] = $newPayment->id;
                    }
                }

                // Delete payments that are not in the list anymore
                $order->payments()->whereNotIn('id', $existingPaymentIds)->delete();
            }

            // Update discount usage counts
            if ($oldDiscountId && $oldDiscountId !== $order->discount_id) {
                // Decrement old discount usage
                $oldDiscount = Discount::find($oldDiscountId);
                if ($oldDiscount) {
                    $oldDiscount->decrementUsage();
                }
            }

            if ($order->discount_id && $oldDiscountId !== $order->discount_id) {
                // Increment new discount usage
                $order->discount->incrementUsage();
            }

            return $order->refresh()->load(['items', 'payments', 'discount']);
        });
    }
}
