<?php

declare(strict_types=1);

use App\Enums\CourseLevelEnum;
use App\Enums\CourseTypeEnum;
use App\Enums\TermStatus;

return [
    'room'            => [
        [
            'name'     => 'کلاس A',
            'location' => 'توضیحات کلاس A',
            'capacity' => 30,
        ],
    ],
    'term'            => [
        [
            'title'       => 'ترم بهار ۱۴۰۳',
            'description' => 'توضیحات ترم بهار ۱۴۰۳',
            'start_date'  => '2025-01-01',
            'end_date'    => '2025-06-01',
            'status'      => TermStatus::ACTIVE->value,
        ],
    ],
    'course_template' => [
        [
            'title'         => 'قالب دوره برنامه‌نویسی',
            'description'   => 'توضیحات قالب دوره برنامه‌نویسی',
            'body'          => 'محتوای کامل قالب دوره برنامه‌نویسی',
            'image'         => public_path('images/test/blogs/laravel.jpg'),
            'category_id'   => 1,
            'level'         => CourseLevelEnum::BIGGINER->value,
            'type'          => CourseTypeEnum::IN_PERSON->value,
            'capacity'      => 25,
            'tags'          => ['programming', 'web development'],
            'prerequisites' => [],
            'is_self_paced' => false,
            'sessions'      => [
                [
                    'title'            => 'جلسه اول',
                    'description'      => 'توضیحات جلسه اول',
                    'duration_minutes' => '60', // min
                    'order'            => 1,
                ],
                [
                    'title'            => 'جلسه دوم',
                    'description'      => 'توضیحات جلسه دوم',
                    'duration_minutes' => '60', // min
                    'order'            => 2,
                ],
            ],
        ],
    ],
    'course'          => [
        [
            'course_template_id' => 1,
            'term_id'            => 1,
            'teacher_id'         => 2,
            'price'              => 1000000,
            'capacity'           => 100,
            'sessions'           => [
                [
                    'course_session_template_id' => 1,
                    'date'                       => '2025-01-01',
                    'start_time'                 => '10:00:00',
                    'end_time'                   => '12:00:00',
                    'room_id'                    => 1,
                    'meeting_link'               => null,
                    'session_number'             => 1,
                ],
                [
                    'course_session_template_id' => 2,
                    'date'                       => '2025-01-08',
                    'start_time'                 => '10:00:00',
                    'end_time'                   => '12:00:00',
                    'room_id'                    => 1,
                    'meeting_link'               => null,
                    'session_number'             => 2,
                ],
            ],
        ],
    ],
    'order'           => [
        [
            'user_id'      => 2,
            'total_amount' => 1000000,
            'status'       => 'pending',
        ],
    ],
    'payment'         => [
        [
            'user_id'        => 2,
            'order_id'       => 1,
            'amount'         => 1000000,
            'type'           => 'full_online',
            'status'         => 'pending',
            'transaction_id' => 'TXN123456',
        ],
    ],
    'installment'     => [
        [
            'payment_id'     => 1,
            'amount'         => 500000,
            'due_date'       => '2025-02-01',
            'method'         => 'online',
            'status'         => 'pending',
            'transaction_id' => null,
        ],
    ],
    'enrollment'      => [
        [
            'user_id'     => 2,
            'course_id'   => 1,
            'enroll_date' => '2025-01-01',
            'status'      => 'active',
        ],
    ],
    'attendance'      => [
        [
            'enrollment_id' => 1,
            'session_id'    => 1,
            'present'       => true,
            'arrival_time'  => '2025-01-01 09:55:00',
            'leave_time'    => '2025-01-01 12:05:00',
        ],
    ],
];
