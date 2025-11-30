<?php

declare(strict_types=1);

use App\Enums\CategoryTypeEnum;
use App\Enums\CourseLevelEnum;
use App\Enums\CourseTypeEnum;
use App\Enums\TermStatus;
use App\Models\Category;

return [
    'room' => [
        [
            'name' => 'کلاس ۱۰۱',
            'location' => 'طبقه اول - کلاس ۱۰۱',
            'capacity' => 20,
        ],
        [
            'name' => 'کلاس ۱۰۲',
            'location' => 'طبقه اول - کلاس ۱۰۲',
            'capacity' => 25,
        ],
        [
            'name' => 'کلاس ۲۰۱',
            'location' => 'طبقه دوم - کلاس ۲۰۱',
            'capacity' => 30,
        ],
    ],
    'term' => [
        [
            'title' => 'ترم بهار ۱۴۰۴',
            'description' => 'ترم بهار ۱۴۰۴ - دوره‌های زبان انگلیسی',
            'start_date' => '2025-03-21',
            'end_date' => '2025-06-20',
            'status' => TermStatus::ACTIVE->value,
        ],
    ],
    'course_template' => [
        [
            'title' => 'انگلیسی مبتدی (Beginner)',
            'description' => 'دوره آموزش زبان انگلیسی برای مبتدیان - یادگیری الفبا، اعداد، جملات ساده و مکالمات روزمره',
            'body' => 'این دوره برای افرادی طراحی شده که هیچ آشنایی قبلی با زبان انگلیسی ندارند. در این دوره با الفبای انگلیسی، اعداد، رنگ‌ها، اعضای خانواده و جملات ساده روزمره آشنا می‌شوید.',
            'category_id' => Category::where('type', CategoryTypeEnum::COURSE)->first()?->id,
            'level' => CourseLevelEnum::BIGGINER->value,
            'type' => CourseTypeEnum::IN_PERSON->value,
            'tags' => ['english', 'beginner', 'language'],
            'prerequisites' => [],
            'is_self_paced' => false,
            'sessions' => [
                [
                    'title' => 'معرفی و الفبای انگلیسی',
                    'description' => 'آشنایی با الفبای انگلیسی و تلفظ صحیح حروف',
                    'duration_minutes' => 90,
                    'order' => 1,
                ],
                [
                    'title' => 'اعداد و رنگ‌ها',
                    'description' => 'یادگیری اعداد ۱ تا ۱۰۰ و نام رنگ‌ها',
                    'duration_minutes' => 90,
                    'order' => 2,
                ],
                [
                    'title' => 'اعضای خانواده',
                    'description' => 'یادگیری نام اعضای خانواده و روابط خانوادگی',
                    'duration_minutes' => 90,
                    'order' => 3,
                ],
                [
                    'title' => 'جملات ساده روزمره',
                    'description' => 'یادگیری جملات ساده برای احوال‌پرسی و معرفی خود',
                    'duration_minutes' => 90,
                    'order' => 4,
                ],
                [
                    'title' => 'مکالمه پایه',
                    'description' => 'تمرین مکالمه در موقعیت‌های ساده روزمره',
                    'duration_minutes' => 90,
                    'order' => 5,
                ],
            ],
        ],
        [
            'title' => 'انگلیسی متوسط (Intermediate)',
            'description' => 'دوره آموزش زبان انگلیسی برای سطح متوسط - تقویت گرامر، واژگان و مهارت‌های مکالمه',
            'body' => 'این دوره برای افرادی طراحی شده که آشنایی اولیه با زبان انگلیسی دارند و می‌خواهند مهارت‌های خود را تقویت کنند. در این دوره با گرامر پیشرفته‌تر، واژگان بیشتر و مکالمات پیچیده‌تر آشنا می‌شوید.',
            'category_id' => Category::where('type', CategoryTypeEnum::COURSE)->first()?->id,
            'level' => CourseLevelEnum::INTERMEDIATE->value,
            'type' => CourseTypeEnum::IN_PERSON->value,
            'tags' => ['english', 'intermediate', 'language'],
            'prerequisites' => [],
            'is_self_paced' => false,
            'sessions' => [
                [
                    'title' => 'مرور و تقویت پایه',
                    'description' => 'مرور مطالب پایه و تقویت مهارت‌های قبلی',
                    'duration_minutes' => 90,
                    'order' => 1,
                ],
                [
                    'title' => 'گرامر پیشرفته',
                    'description' => 'یادگیری زمان‌های مختلف و ساختارهای پیچیده‌تر',
                    'duration_minutes' => 90,
                    'order' => 2,
                ],
                [
                    'title' => 'واژگان و اصطلاحات',
                    'description' => 'افزایش دایره واژگان و یادگیری اصطلاحات رایج',
                    'duration_minutes' => 90,
                    'order' => 3,
                ],
                [
                    'title' => 'مکالمه پیشرفته',
                    'description' => 'تمرین مکالمه در موقعیت‌های پیچیده‌تر',
                    'duration_minutes' => 90,
                    'order' => 4,
                ],
            ],
        ],
    ],
    'course' => [
        [
            'term_id' => 1,
            'price' => 2500000,
            'capacity' => 20,
            'sessions' => [
                [
                    'course_session_template_id' => 1,
                    'date' => '2025-03-22',
                    'start_time' => '09:00',
                    'end_time' => '10:30',
                    'room_id' => 1,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
                [
                    'course_session_template_id' => 2,
                    'date' => '2025-03-29',
                    'start_time' => '09:00',
                    'end_time' => '10:30',
                    'room_id' => 1,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
                [
                    'course_session_template_id' => 3,
                    'date' => '2025-04-05',
                    'start_time' => '09:00',
                    'end_time' => '10:30',
                    'room_id' => 1,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
                [
                    'course_session_template_id' => 4,
                    'date' => '2025-04-12',
                    'start_time' => '09:00',
                    'end_time' => '10:30',
                    'room_id' => 1,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
                [
                    'course_session_template_id' => 5,
                    'date' => '2025-04-19',
                    'start_time' => '09:00',
                    'end_time' => '10:30',
                    'room_id' => 1,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
            ],
        ],
        [
            'term_id' => 1,
            'price' => 3000000,
            'capacity' => 25,
            'sessions' => [
                [
                    'course_session_template_id' => 1,
                    'date' => '2025-03-23',
                    'start_time' => '14:00',
                    'end_time' => '15:30',
                    'room_id' => 2,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
                [
                    'course_session_template_id' => 2,
                    'date' => '2025-03-30',
                    'start_time' => '14:00',
                    'end_time' => '15:30',
                    'room_id' => 2,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
                [
                    'course_session_template_id' => 3,
                    'date' => '2025-04-06',
                    'start_time' => '14:00',
                    'end_time' => '15:30',
                    'room_id' => 2,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
                [
                    'course_session_template_id' => 4,
                    'date' => '2025-04-13',
                    'start_time' => '14:00',
                    'end_time' => '15:30',
                    'room_id' => 2,
                    'meeting_link' => null,
                    'session_type' => App\Enums\SessionType::IN_PERSON->value,
                    'status' => App\Enums\SessionStatus::PLANNED->value,
                ],
            ],
        ],
    ],
    'order' => [
        [
            'user_id' => 2,
            'total_amount' => 1000000,
            'status' => 'pending',
        ],
    ],
    'payment' => [
        [
            'user_id' => 2,
            'order_id' => 1,
            'amount' => 1000000,
            'type' => 'full_online',
            'status' => 'pending',
            'transaction_id' => 'TXN123456',
        ],
    ],
    'installment' => [
        [
            'payment_id' => 1,
            'amount' => 500000,
            'due_date' => '2025-02-01',
            'method' => 'online',
            'status' => 'pending',
            'transaction_id' => null,
        ],
    ],
    'enrollment' => [
        [
            'user_id' => 2,
            'course_id' => 1,
            'enroll_date' => '2025-01-01',
            'status' => 'active',
        ],
    ],
    'attendance' => [
        [
            'enrollment_id' => 1,
            'session_id' => 1,
            'present' => true,
            'arrival_time' => '2025-01-01 09:55:00',
            'leave_time' => '2025-01-01 12:05:00',
        ],
    ],
];
