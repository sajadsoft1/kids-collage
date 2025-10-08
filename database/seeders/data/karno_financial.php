<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;

return [
    'order' => [
        // Completed orders
        [
            'user_id'         => 1,
            'discount_id'     => null,
            'pure_amount'     => 150000,
            'discount_amount' => 0,
            'total_amount'    => 150000,
            'status'          => OrderStatusEnum::COMPLETED->value,
            'note'            => 'First completed order for course enrollment',
        ],
        [
            'user_id'         => 2,
            'discount_id'     => null,
            'pure_amount'     => 200000,
            'discount_amount' => 0,
            'total_amount'    => 200000,
            'status'          => OrderStatusEnum::COMPLETED->value,
            'note'            => 'Order completed successfully',
        ],
        [
            'user_id'         => 3,
            'discount_id'     => null,
            'pure_amount'     => 350000,
            'discount_amount' => 0,
            'total_amount'    => 350000,
            'status'          => OrderStatusEnum::COMPLETED->value,
            'note'            => 'Multiple course enrollment',
        ],

        // Processing orders
        [
            'user_id'         => 4,
            'discount_id'     => null,
            'pure_amount'     => 120000,
            'discount_amount' => 0,
            'total_amount'    => 120000,
            'status'          => OrderStatusEnum::PROCESSING->value,
            'note'            => 'Payment received, processing enrollment',
        ],
        [
            'user_id'         => 5,
            'discount_id'     => null,
            'pure_amount'     => 250000,
            'discount_amount' => 0,
            'total_amount'    => 250000,
            'status'          => OrderStatusEnum::PROCESSING->value,
            'note'            => 'Payment received, processing enrollment',
        ],

        // Pending orders
        [
            'user_id'         => 6,
            'discount_id'     => null,
            'pure_amount'     => 100000,
            'discount_amount' => 0,
            'total_amount'    => 100000,
            'status'          => OrderStatusEnum::PENDING->value,
            'note'            => 'Waiting for payment confirmation',
        ],
        [
            'user_id'         => 7,
            'discount_id'     => null,
            'pure_amount'     => 180000,
            'discount_amount' => 0,
            'total_amount'    => 180000,
            'status'          => OrderStatusEnum::PENDING->value,
            'note'            => 'New order, payment pending',
        ],
        [
            'user_id'         => 8,
            'discount_id'     => null,
            'pure_amount'     => 300000,
            'discount_amount' => 0,
            'total_amount'    => 300000,
            'status'          => OrderStatusEnum::PENDING->value,
            'note'            => 'Bulk enrollment order',
        ],

        // Cancelled orders
        [
            'user_id'         => 9,
            'discount_id'     => null,
            'pure_amount'     => 150000,
            'discount_amount' => 0,
            'total_amount'    => 150000,
            'status'          => OrderStatusEnum::CANCELLED->value,
            'note'            => 'Cancelled by customer request',
        ],
        [
            'user_id'         => 10,
            'discount_id'     => null,
            'pure_amount'     => 90000,
            'discount_amount' => 0,
            'total_amount'    => 90000,
            'status'          => OrderStatusEnum::CANCELLED->value,
            'note'            => 'Payment failed after multiple attempts',
        ],

        // Additional completed orders with various amounts
        [
            'user_id'         => 1,
            'discount_id'     => null,
            'pure_amount'     => 400000,
            'discount_amount' => 0,
            'total_amount'    => 400000,
            'status'          => OrderStatusEnum::COMPLETED->value,
            'note'            => 'Second order completed',
        ],
        [
            'user_id'         => 2,
            'discount_id'     => null,
            'pure_amount'     => 220000,
            'discount_amount' => 0,
            'total_amount'    => 220000,
            'status'          => OrderStatusEnum::COMPLETED->value,
            'note'            => 'Summer course enrollment',
        ],
        [
            'user_id'         => 3,
            'discount_id'     => null,
            'pure_amount'     => 175000,
            'discount_amount' => 0,
            'total_amount'    => 175000,
            'status'          => OrderStatusEnum::COMPLETED->value,
            'note'            => 'Additional course enrollment',
        ],

        // More pending orders
        [
            'user_id'         => 4,
            'discount_id'     => null,
            'pure_amount'     => 130000,
            'discount_amount' => 0,
            'total_amount'    => 130000,
            'status'          => OrderStatusEnum::PENDING->value,
            'note'            => 'Second enrollment pending',
        ],
        [
            'user_id'         => 5,
            'discount_id'     => null,
            'pure_amount'     => 160000,
            'discount_amount' => 0,
            'total_amount'    => 160000,
            'status'          => OrderStatusEnum::PENDING->value,
            'note'            => 'New enrollment pending',
        ],
    ],
];
