<?php

declare(strict_types=1);

return [
    'model' => 'جلسه',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
        'type' => [
            'in_person' => 'حضوری',
            'online' => 'آنلاین',
            'hybrid' => 'هایبرید',
            'self_paced' => 'فروش آنلاین',
        ],
        'status' => [
            'planned' => 'برنامه‌ریزی شده',
            'done' => 'انجام شده',
            'cancelled' => 'لغو شده',
        ],
    ],
    'page' => [
        'session_list' => 'جلسات دوره',
        'session' => 'جلسه',
        'no_sessions' => 'هنوز جلسه‌ای برای این دوره ایجاد نشده است',
        'select_session' => 'لطفاً یک جلسه را از لیست انتخاب کنید',
        'loading_session' => 'در حال بارگذاری جلسه...',
        'session_link' => 'لینک جلسه',
        'recording_link' => 'ضبط جلسه',
    ],
];
