<?php

declare(strict_types=1);

return [
    'model' => 'Session',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
        'type' => [
            'in_person' => 'In-Person',
            'online' => 'Online',
            'hybrid' => 'Hybrid',
            'self_paced' => 'Self-Paced',
        ],
        'status' => [
            'planned' => 'Planned',
            'done' => 'Done',
            'cancelled' => 'Cancelled',
        ],
    ],
    'page' => [
        'session_list' => 'Course Sessions',
        'session' => 'Session',
        'no_sessions' => 'No sessions have been created for this course yet',
        'select_session' => 'Please select a session from the list',
        'loading_session' => 'Loading session...',
        'session_link' => 'Session Link',
        'recording_link' => 'Recording',
    ],
];
