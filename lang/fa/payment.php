<?php

declare(strict_types=1);

return [
    'model' => 'پرداخت',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
        'status' => [
            'pending' => 'در انتظار پرداخت',
            'paid' => 'پرداخت شده',
            'failed' => 'ناموفق',
        ],
        'type' => [
            'online' => 'آنلاین',
            'cash' => 'نقدی',
            'card_to_card' => 'کارت به کارت',
        ],
    ],
    'notifications' => [
    ],
    'page' => [
    ],
];
