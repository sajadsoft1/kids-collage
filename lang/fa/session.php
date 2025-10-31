<?php

declare(strict_types=1);

return [
    'model'       => 'جلسه',
    'permissions' => [
    ],
    'exceptions'  => [
    ],
    'validations' => [
    ],
    'enum'        => [
        'type' => [
            'in_person'  => 'حضوری',
            'online'     => 'آنلاین',
            'hybrid'     => 'هایبرید',
            'self_paced' => 'فروش آنلاین',
        ],
    ],
];
