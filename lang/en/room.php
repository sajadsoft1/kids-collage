<?php

declare(strict_types=1);

return [
    'model' => 'Room',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
    ],
    'notifications' => [
    ],
    'page' => [
    ],

    'learning' => [
        'index' => [
            'title' => 'Room List',
            'content' => '<p>List of all rooms. Each room has a name, location and capacity. Use this list to manage rooms for scheduling course sessions.</p>',
        ],
        'create' => [
            'title' => 'Creating a room',
            'content' => '<p>To create a new room, enter the name, location and capacity, then click submit.</p>',
        ],
        'edit' => [
            'title' => 'Editing a room',
            'content' => '<p>To edit a room, change the desired fields and click submit.</p>',
        ],
    ],
];
