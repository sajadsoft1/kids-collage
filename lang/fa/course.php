<?php

declare(strict_types=1);

return [
    'model'         => 'دوره',
    'permissions'   => [
        'view_course'   => 'مشاهده دوره',
        'create_course' => 'ایجاد دوره',
        'update_course' => 'ویرایش دوره',
        'delete_course' => 'حذف دوره',
    ],
    'exceptions'    => [
        'course_not_found'     => 'دوره مورد نظر یافت نشد',
        'course_access_denied' => 'دسترسی به دوره مورد نظر مجاز نیست',
    ],
    'validations'   => [
        'title_required'       => 'عنوان دوره الزامی است',
        'description_required' => 'توضیحات دوره الزامی است',
        'price_required'       => 'قیمت دوره الزامی است',
        'start_date_required'  => 'تاریخ شروع دوره الزامی است',
        'end_date_required'    => 'تاریخ پایان دوره الزامی است',
        'end_date_after_start' => 'تاریخ پایان باید بعد از تاریخ شروع باشد',
    ],
    'enum'          => [
        'type'   => [
            'in_person'  => 'حضوری',
            'online'     => 'آنلاین',
            'hybrid'     => 'ترکیبی',
            'self-paced' => 'آنلاین (خودرسانه‌ای)',
        ],
        'status' => [
            'draft'     => 'پیش‌نویس',
            'scheduled' => 'زمان‌بندی شده',
            'active'    => 'فعال',
            'finished'  => 'اتمام یافته',
            'cancelled' => 'لغو شده',
        ],
    ],
    'notifications' => [
        'course_created' => 'دوره با موفقیت ایجاد شد',
        'course_updated' => 'دوره با موفقیت ویرایش شد',
        'course_deleted' => 'دوره با موفقیت حذف شد',
    ],
    'page'          => [
        'index_title'  => 'لیست دوره‌ها',
        'create_title' => 'ایجاد دوره جدید',
        'edit_title'   => 'ویرایش دوره',
        'show_title'   => 'جزئیات دوره',
        'runer'        => [
            'capacity_info'   => 'ظرفیت کلاس در حین اجرای دوره قابل تغییر میباشد.',
            'price_info'      => 'قیمت دوره در حین اجرای دوره قابل تغییر میباشد.',
            'status_info'     => 'حالت دوره در حین اجرای دوره قابل تغییر میباشد.',
            'term_info'       => 'ترم دوره در حین اجرای دوره قابل تغییر میباشد.',
            'teacher_info'    => 'استاد دوره در حین اجرای دوره قابل تغییر میباشد.',
            'room_info'       => 'اتاق دوره در حین اجرای دوره قابل تغییر میباشد.',
            'start_date_info' => 'تاریخ شروع دوره در حین اجرای دوره قابل تغییر میباشد.',
            'end_date_info'   => 'تاریخ پایان دوره در حین اجرای دوره قابل تغییر میباشد.',
            'start_time_info' => 'ساعت شروع دوره در حین اجرای دوره قابل تغییر میباشد.',
            'end_time_info'   => 'ساعت پایان دوره در حین اجرای دوره قابل تغییر میباشد.',
            'week_days_info'  => 'روزهای اجرای دوره در حین اجرای دوره قابل تغییر میباشد.',
        ],
    ],
];
