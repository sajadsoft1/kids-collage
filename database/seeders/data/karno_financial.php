<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;

return [
    'order' => [
        [
            'user_id'      => 1,
            'total_amount' => 100000,
            'status'       => OrderStatusEnum::PENDING->value,
        ],
    ],
];
