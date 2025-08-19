<?php

declare(strict_types=1);

return [
    'model'         => 'بورد',
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
    ],
    'notifications' => [
    ],
    'page'          => [
    ],
    'board_list'    => [
        'title'         => 'بوردها',
        'create'        => 'افزودن بورد',
        'search_boards' => 'جستجوی بوردها',

        'card'          => [
            'card_admin' => 'مدیر: :name',
            'cards'      => 'کارت ها',
            'columns'    => 'ستون ها',
        ],
    ],
    'kanban'        => [
        'title'       => 'کانبان',
        'create_card' => 'افزودن کارت',
    ],
];
