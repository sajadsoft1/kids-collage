<?php

declare(strict_types=1);

return [
    'model' => 'دوره',
    'permissions' => [
        'view_course' => 'مشاهده دوره',
        'create_course' => 'ایجاد دوره',
        'update_course' => 'ویرایش دوره',
        'delete_course' => 'حذف دوره',
    ],
    'exceptions' => [
        'course_not_found' => 'دوره مورد نظر یافت نشد',
        'course_access_denied' => 'دسترسی به دوره مورد نظر مجاز نیست',
        'only_draft_courses_can_be_published' => 'فقط دوره‌های در حالت پیش‌نویس می‌توانند منتشر شوند',
        'course_is_not_in_a_state_that_can_be_started' => 'دوره در حالتی نیست که بتوان آن را شروع کرد',
        'only_active_courses_can_be_finished' => 'فقط دوره‌های فعال می‌توانند به اتمام برسند',
        'finished_courses_cannot_be_cancelled' => 'دوره‌های به اتمام رسیده نمی‌توانند لغو شوند',
    ],
    'validations' => [
        'title_required' => 'عنوان دوره الزامی است',
        'description_required' => 'توضیحات دوره الزامی است',
        'price_required' => 'قیمت دوره الزامی است',
        'start_date_required' => 'تاریخ شروع دوره الزامی است',
        'end_date_required' => 'تاریخ پایان دوره الزامی است',
        'end_date_after_start' => 'تاریخ پایان باید بعد از تاریخ شروع باشد',
        'select_a_room' => 'انتخاب یک اتاق الزامی است',
        'select_a_term' => 'انتخاب یک ترم الزامی است',
        'select_a_teacher' => 'انتخاب یک استاد الزامی است',
        'select_a_status' => 'انتخاب وضعیت الزامی است',
    ],
    'enum' => [
        'type' => [
            'in_person' => 'حضوری',
            'online' => 'آنلاین',
            'hybrid' => 'ترکیبی',
            'self_paced' => 'فروش آنلاین',
        ],
        'status' => [
            'draft' => 'پیش‌نویس',
            'scheduled' => 'زمان‌بندی شده',
            'active' => 'فعال',
            'finished' => 'اتمام یافته',
            'cancelled' => 'لغو شده',
        ],
        'level' => [
            'bigginer' => 'بیگینر',
            'normal' => 'نرمال',
            'advance' => 'پیشرفته',
            'intermediate' => 'میانه',
        ],
    ],
    'notifications' => [
        'course_created' => 'دوره با موفقیت ایجاد شد',
        'course_updated' => 'دوره با موفقیت ویرایش شد',
        'course_deleted' => 'دوره با موفقیت حذف شد',
    ],
    'page' => [
        'index_title' => 'لیست دوره‌ها',
        'create_title' => 'ایجاد دوره جدید',
        'edit_title' => 'ویرایش دوره',
        'show_title' => 'جزئیات دوره',
        'session_list' => 'لیست جلسات دوره',

        'runer' => [
            'generated_sessions' => 'جلسات تولید شده با توجه به بازه انتخابی',
            'capacity_info' => 'ظرفیت کلاس در حین اجرای دوره قابل تغییر میباشد.',
            'price_info' => '',
            'status_info' => '',
            'term_info' => '',
            'teacher_info' => '',
            'room_info' => '',
            'start_date_info' => '',
            'end_date_info' => '',
            'start_time_info' => '',
            'end_time_info' => '',
            'week_days_info' => '',
        ],
    ],
];
