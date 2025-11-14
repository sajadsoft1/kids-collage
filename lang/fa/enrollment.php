<?php

declare(strict_types=1);

return [
    'model' => 'ثبت نام',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
        'status' => [
            'pending' => 'در انتظار',
            'paid' => 'پرداخت شده',
            'active' => 'فعال',
            'dropped' => 'لغو شده',
        ],
    ],
    'notifications' => [
    ],
    'page' => [
    ],
];
