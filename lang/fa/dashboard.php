<?php

declare(strict_types=1);

return [
    'title' => 'داشبورد',
    'refresh' => 'بروزرسانی',
    'refreshing' => 'در حال بروزرسانی',
    'refresh_error' => 'خطا در بروزرسانی اطلاعات داشبورد',
    'loading' => 'در حال بارگذاری...',

    'statistics' => [
        'total_users' => 'کل کاربران',
        'total_blogs' => 'کل مقالات',
        'unresolved_tickets' => 'تیکت‌های حل نشده',
        'last_month_users' => 'کاربران ماه گذشته',
        'total_contacts' => 'کل تماس‌ها',
        'total_portfolios' => 'کل نمونه کارها',
        'total_categories' => 'کل دسته‌بندی‌ها',
        'total_opinions' => 'کل نظرات',
    ],

    'today' => [
        'title' => 'آمار امروز',
        'new_users' => 'کاربران جدید',
        'new_blogs' => 'مقالات جدید',
        'new_tickets' => 'تیکت‌های جدید',
        'new_contacts' => 'تماس‌های جدید',
        'weekly_growth' => 'رشد هفتگی',
    ],

    'charts' => [
        'users_registration' => 'ثبت‌نام کاربران در طول زمان',
        'user_gender_distribution' => 'توزیع جنسیت کاربران',
        'monthly_growth' => 'رشد ماهانه',
        'ticket_status_distribution' => 'توزیع وضعیت تیکت‌ها',
        'total_tickets' => 'کل تیکت‌ها',
        'total_users' => 'کل کاربران',
        'periods' => [
            'today' => 'امروز',
            'week' => 'هفته گذشته',
            'month' => 'ماه گذشته',
            'year' => 'سال گذشته',
        ],
        'genders' => [
            'men' => 'مردان',
            'women' => 'زنان',
        ],
        'metrics' => [
            'users' => 'کاربران',
            'blogs' => 'مقالات',
            'tickets' => 'تیکت‌ها',
        ],
    ],

    'tickets' => [
        'last_tickets' => 'آخرین تیکت‌ها',
        'ticket_number' => 'شماره تیکت',
        'subject' => 'موضوع',
        'user' => 'کاربر',
        'priority' => 'اولویت',
        'status' => 'وضعیت',
        'created_at' => 'تاریخ ایجاد',
        'actions' => 'عملیات',
        'view_all_tickets' => 'مشاهده همه تیکت‌ها',
        'no_tickets_found' => 'تیکتی یافت نشد',
        'priorities' => [
            'low' => 'کم',
            'medium' => 'متوسط',
            'high' => 'زیاد',
            'unknown' => 'نامشخص',
        ],
        'statuses' => [
            'open' => 'باز',
            'close' => 'بسته',
        ],
    ],

    'recent' => [
        'recent_users' => 'کاربران اخیر',
        'recent_blogs' => 'مقالات اخیر',
        'view_all' => 'مشاهده همه',
        'no_data' => 'داده‌ای یافت نشد',
    ],

    'system' => [
        'system_health' => 'سلامت سیستم',
        'php_version' => 'نسخه PHP',
        'laravel_version' => 'نسخه Laravel',
        'database_status' => 'وضعیت پایگاه داده',
        'cache_status' => 'وضعیت کش',
        'queue_status' => 'وضعیت صف',
        'connected' => 'متصل',
        'active' => 'فعال',
        'running' => 'در حال اجرا',
    ],

    'quick_actions' => [
        'title' => 'عملیات سریع',
        'add_user' => 'افزودن کاربر',
        'create_blog' => 'ایجاد مقاله',
        'view_tickets' => 'مشاهده تیکت‌ها',
        'manage_categories' => 'مدیریت دسته‌بندی‌ها',
    ],

    'notifications' => [
        'success' => 'موفقیت',
        'error' => 'خطا',
        'warning' => 'هشدار',
        'info' => 'اطلاعات',
    ],

    'summary' => [
        'title' => 'خلاصه داشبورد',
        'total_items' => 'کل آیتم‌ها',
        'growth' => 'رشد',
        'status' => 'وضعیت',
        'last_updated' => 'آخرین بروزرسانی',
        'weekly' => [
            'percentage' => 'درصد رشد هفتگی',
        ],
    ],

    'performance' => [
        'title' => 'معیارهای عملکرد',
        'database_query_time' => 'زمان پرس و جوی پایگاه داده',
        'cache_hit_rate' => 'نرخ برخورد کش',
        'total_response_time' => 'کل زمان پاسخ',
        'memory_usage' => 'استفاده از حافظه',
        'peak_memory' => 'حداکثر حافظه',
        'seconds' => 'ثانیه',
        'mb' => 'مگابایت',
        'percent' => 'درصد',
    ],

    'date_filter' => [
        'title' => 'فیلتر تاریخ',
        'start_date' => 'تاریخ شروع',
        'end_date' => 'تاریخ پایان',
        'apply' => 'اعمال',
        'cancel' => 'لغو',
        'custom_range' => 'محدوده سفارشی',
        'showing_data_from' => 'نمایش داده‌ها از',
        'to' => 'تا',
        'periods' => [
            'today' => 'امروز',
            'yesterday' => 'دیروز',
            'last_7_days' => '۷ روز گذشته',
            'last_30_days' => '۳۰ روز گذشته',
            'last_90_days' => '۹۰ روز گذشته',
            'this_month' => 'این ماه',
            'last_month' => 'ماه گذشته',
            'this_year' => 'امسال',
            'custom' => 'محدوده سفارشی',
        ],
    ],
];
