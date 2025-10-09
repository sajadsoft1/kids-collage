<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Models\Course;
use App\Models\Discount;

return [
    'order_course' => [
        // ============================================================================
        // COMPLETED ORDERS - Cash Payments (2 orders)
        // ============================================================================
        [
            'user_id'     => 1,
            'discount_id' => Discount::where('code', 'CASH50K')->first()->id,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::COMPLETED->value,
            'note'        => 'Cash payment completed',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price - 50000,
                    'scheduled_date' => now()->subDays(10)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::CASH->value,
                    'status'         => PaymentStatusEnum::PAID->value,
                    'note'           => 'Full cash payment received at office',
                ],
            ],
        ],
        [
            'user_id'     => 2,
            'discount_id' => Discount::where('code', 'CASH50K')->first()->id,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::COMPLETED->value,
            'note'        => 'Cash payment completed',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price - 50000,
                    'scheduled_date' => now()->subDays(8)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::CASH->value,
                    'status'         => PaymentStatusEnum::PAID->value,
                    'note'           => 'Cash payment received',
                ],
            ],
        ],

        // ============================================================================
        // COMPLETED ORDERS - Card to Card Payments (2 orders)
        // ============================================================================
        [
            'user_id'     => 3,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::COMPLETED->value,
            'note'        => 'Card to card payment completed',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'           => Course::find(1)->price,
                    'scheduled_date'   => now()->subDays(5)->format('Y-m-d'),
                    'type'             => PaymentTypeEnum::CARD_TO_CARD->value,
                    'status'           => PaymentStatusEnum::PAID->value,
                    'last_card_digits' => '1234',
                    'tracking_code'    => 'TRC-' . str_pad('1', 10, '0', STR_PAD_LEFT),
                    'note'             => 'Card to card transfer completed',
                ],
            ],
        ],
        [
            'user_id'     => 4,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::COMPLETED->value,
            'note'        => 'Card to card payment completed',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'           => Course::find(1)->price,
                    'scheduled_date'   => now()->subDays(12)->format('Y-m-d'),
                    'type'             => PaymentTypeEnum::CARD_TO_CARD->value,
                    'status'           => PaymentStatusEnum::PAID->value,
                    'last_card_digits' => '5678',
                    'tracking_code'    => 'TRC-' . str_pad('2', 10, '0', STR_PAD_LEFT),
                    'note'             => 'Card to card transfer received',
                ],
            ],
        ],

        // ============================================================================
        // COMPLETED ORDERS - Online Payments with payment_link and transaction_id
        // ============================================================================
        [
            'user_id'     => 5,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::COMPLETED->value,
            'note'        => 'Online payment completed',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->subDays(2)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::ONLINE->value,
                    'status'         => PaymentStatusEnum::PAID->value,
                    'payment_link'   => 'https://payment.example.com/pay/abc123',
                    'transaction_id' => 1,
                    'note'           => 'Online payment successful',
                ],
            ],
        ],
        [
            'user_id'     => 6,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::COMPLETED->value,
            'note'        => 'Online payment completed',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->subDays(7)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::ONLINE->value,
                    'status'         => PaymentStatusEnum::PAID->value,
                    'payment_link'   => 'https://payment.example.com/pay/def456',
                    'transaction_id' => 2,
                    'note'           => 'Online payment via gateway',
                ],
            ],
        ],
        [
            'user_id'     => 7,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::COMPLETED->value,
            'note'        => 'Online payment completed',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->subDays(15)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::ONLINE->value,
                    'status'         => PaymentStatusEnum::PAID->value,
                    'payment_link'   => 'https://payment.example.com/pay/ghi789',
                    'transaction_id' => 3,
                    'note'           => 'Online payment processed',
                ],
            ],
        ],

        // ============================================================================
        // PENDING ORDERS - Cash Payments (2 orders)
        // ============================================================================
        [
            'user_id'     => 8,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::PENDING->value,
            'note'        => 'Waiting for cash payment',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::CASH->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'note'           => 'Scheduled to pay cash at office',
                ],
            ],
        ],
        [
            'user_id'     => 9,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::PENDING->value,
            'note'        => 'Waiting for cash payment',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->addDays(1)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::CASH->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'note'           => 'Will pay cash tomorrow',
                ],
            ],
        ],

        // ============================================================================
        // PENDING ORDERS - Card to Card Payments (2 orders)
        // ============================================================================
        [
            'user_id'     => 10,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::PENDING->value,
            'note'        => 'Waiting for card to card transfer',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::CARD_TO_CARD->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'note'           => 'Waiting for card to card transfer',
                ],
            ],
        ],
        [
            'user_id'     => 1,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::PENDING->value,
            'note'        => 'Waiting for card to card transfer',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->addDays(2)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::CARD_TO_CARD->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'note'           => 'Card transfer scheduled',
                ],
            ],
        ],

        // ============================================================================
        // PENDING ORDERS - Online Payments with payment_link
        // ============================================================================
        [
            'user_id'     => 2,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::PENDING->value,
            'note'        => 'Waiting for online payment',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::ONLINE->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'payment_link'   => 'https://payment.example.com/pay/pending001',
                    'note'           => 'Payment link sent to user',
                ],
            ],
        ],
        [
            'user_id'     => 3,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::PENDING->value,
            'note'        => 'Waiting for online payment',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price,
                    'scheduled_date' => now()->addDays(1)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::ONLINE->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'payment_link'   => 'https://payment.example.com/pay/pending002',
                    'note'           => 'Awaiting online payment',
                ],
            ],
        ],

        // ============================================================================
        // CANCELLED ORDERS (2 orders)
        // ============================================================================

        // Cancelled Order 1: One payment PAID + One payment PENDING
        [
            'user_id'     => 4,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::CANCELLED->value,
            'note'        => 'Cancelled after first payment',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'           => Course::find(1)->price / 2,
                    'scheduled_date'   => now()->subDays(3)->format('Y-m-d'),
                    'type'             => PaymentTypeEnum::CARD_TO_CARD->value,
                    'status'           => PaymentStatusEnum::PAID->value,
                    'last_card_digits' => '9012',
                    'tracking_code'    => 'TRC-' . str_pad('3', 10, '0', STR_PAD_LEFT),
                    'note'             => 'First installment paid before cancellation',
                ],
                [
                    'amount'         => Course::find(1)->price / 2,
                    'scheduled_date' => now()->addDays(30)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::CARD_TO_CARD->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'note'           => 'Second installment cancelled',
                ],
            ],
        ],

        // Cancelled Order 2: All payments PENDING
        [
            'user_id'     => 5,
            'discount_id' => null,
            'type'        => OrderTypeEnum::COURSE->value,
            'status'      => OrderStatusEnum::CANCELLED->value,
            'note'        => 'Cancelled before payment',
            'items'       => [
                [
                    'itemable_type' => Course::class,
                    'itemable_id'   => 1,
                    'price'         => Course::find(1)->price,
                    'quantity'      => 1,
                ],
            ],
            'payments'    => [
                [
                    'amount'         => Course::find(1)->price / 2,
                    'scheduled_date' => now()->subDays(2)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::ONLINE->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'payment_link'   => 'https://payment.example.com/pay/cancelled001',
                    'note'           => 'First payment cancelled',
                ],
                [
                    'amount'         => Course::find(1)->price / 2,
                    'scheduled_date' => now()->addDays(15)->format('Y-m-d'),
                    'type'           => PaymentTypeEnum::ONLINE->value,
                    'status'         => PaymentStatusEnum::PENDING->value,
                    'payment_link'   => 'https://payment.example.com/pay/cancelled002',
                    'note'           => 'Second payment cancelled',
                ],
            ],
        ],
    ],
];
