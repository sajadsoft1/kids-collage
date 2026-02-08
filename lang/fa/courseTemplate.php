<?php

declare(strict_types=1);

return [
    'model' => 'قالب دوره',
    'permissions' => [
    ],
    'exceptions' => [
        'not_allowed_to_delete_course_template_due_to_courses' => 'شما اجازه حذف دوره ای که اجرا شده است را ندارید.',
    ],
    'validations' => [
    ],
    'enum' => [
    ],
    'notifications' => [
    ],
    'page' => [
        'session_title_x' => 'جلسه :number',
        'session_description_x' => 'توضیحات جلسه :number',
        'add_session' => 'افزودن جلسه',
        'description_of_session' => 'توضیحات جلسه',
        'run_the_course_template' => 'اجرای دوره',
        'course_template_details' => 'جزئیات نمونه دوره',
        'course_list' => 'لیست دوره‌ها',
        'course_session_template_list' => 'لیست نمونه جلسات',
        'course_template_level_list' => 'سطوح دوره',
        'sessions' => 'جلسات',
    ],
];
