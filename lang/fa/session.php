<?php

declare(strict_types=1);

return [
    'model'         => 'جلسه',
    'permissions'   => [
    ],
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
        'status' => [
            'planned'   => 'برنامه‌ریزی شده',
            'done'      => 'انجام شده',
            'cancelled' => 'لغو شده',
        ],
        'type'   => [
            'in_person' => 'حضوری',
            'online'    => 'آنلاین',
            'hybrid'    => 'ترکیبی',
        ]
    ],
    'notifications' => [
    ],
    'page'          => [
    ],
];
