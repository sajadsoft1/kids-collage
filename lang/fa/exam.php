<?php

declare(strict_types=1);

return [
    'model'         => 'آزمون',
    'permissions'   => [
    ],
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
    ],
    'notifications' => [
    ],
    'page'          => [
        'builder'=>[
            'group'=>[
                'rules'=>[
                    'and'=>'و (AND)',
                    'or'=>'یا (OR)',
                ]
            ],
            'remove_group'=> 'حذف گروه'
        ]
    ],
];
