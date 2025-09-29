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
        'type' => [
            'in-person' => 'حضوری',
            'online'    => 'آنلاین',
            'hybrid'    => 'ترکیبی',
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
    ],
];
