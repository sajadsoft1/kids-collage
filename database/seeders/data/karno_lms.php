<?php

declare(strict_types=1);

return [
    'room'        => [
        [
            'title'       => 'کلاس A',
            'description' => 'توضیحات کلاس A',
            'capacity'    => 30,
            'languages'   => ['fa'],
            'path'        => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'course'      => [
        [
            'title'       => 'دوره لاراول',
            'description' => 'توضیحات دوره لاراول',
            'body'        => 'محتوای کامل دوره لاراول',
            'teacher_id'  => 2,
            'category_id' => 1,
            'price'       => 1000000,
            'type'        => 'in-person',
            'start_date'  => '2025-01-01',
            'end_date'    => '2025-03-01',
            'languages'   => ['fa'],
            'path'        => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'session'     => [
        [
            'title'          => 'جلسه اول',
            'description'    => 'توضیحات جلسه اول',
            'body'           => 'محتوای جلسه اول',
            'course_id'      => 1,
            'teacher_id'     => 2,
            'start_time'     => '2025-01-01 10:00:00',
            'end_time'       => '2025-01-01 12:00:00',
            'room_id'        => 1,
            'meeting_link'   => 'https://meet.example.com',
            'session_number' => 1,
            'languages'      => ['fa'],
            'path'           => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'order'       => [
        [
            'user_id'      => 2,
            'total_amount' => 1000000,
            'status'       => 'pending',
        ],
    ],
    'payment'     => [
        [
            'user_id'        => 2,
            'order_id'       => 1,
            'amount'         => 1000000,
            'type'           => 'full_online',
            'status'         => 'pending',
            'transaction_id' => 'TXN123456',
        ],
    ],
    'installment' => [
        [
            'payment_id'     => 1,
            'amount'         => 500000,
            'due_date'       => '2025-02-01',
            'method'         => 'online',
            'status'         => 'pending',
            'transaction_id' => null,
        ],
    ],
    'enrollment'  => [
        [
            'user_id'     => 2,
            'course_id'   => 1,
            'enroll_date' => '2025-01-01',
            'status'      => 'active',
        ],
    ],
    'attendance'  => [
        [
            'enrollment_id' => 1,
            'session_id'    => 1,
            'present'       => true,
            'arrival_time'  => '2025-01-01 09:55:00',
            'leave_time'    => '2025-01-01 12:05:00',
        ],
    ],
];
