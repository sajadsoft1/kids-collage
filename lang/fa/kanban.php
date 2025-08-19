<?php

declare(strict_types=1);

return [
    // Card Types
    'task'             => 'وظیفه',
    'bug'              => 'خطا',
    'feature'          => 'ویژگی',
    'call'             => 'تماس',
    'meeting'          => 'جلسه',
    'email'            => 'ایمیل',
    'other'            => 'سایر',

    // Priorities
    'priority'         => [
        'low'    => 'کم',
        'medium' => 'متوسط',
        'high'   => 'زیاد',
        'urgent' => 'فوری',
    ],

    // Statuses
    'status'           => [
        'draft'     => 'پیش‌نویس',
        'active'    => 'فعال',
        'completed' => 'تکمیل شده',
        'archived'  => 'بایگانی شده',
    ],

    // Board Roles
    'roles'            => [
        'owner'  => 'مالک',
        'admin'  => 'مدیر',
        'member' => 'عضو',
        'viewer' => 'بیننده',
    ],

    // Card Roles
    'card_roles'       => [
        'assignee' => 'مسئول',
        'reviewer' => 'بازبین',
        'watcher'  => 'ناظر',
    ],

    // Actions
    'actions'          => [
        'create'   => 'ایجاد',
        'edit'     => 'ویرایش',
        'delete'   => 'حذف',
        'move'     => 'انتقال',
        'assign'   => 'تخصیص',
        'unassign' => 'حذف تخصیص',
        'archive'  => 'بایگانی',
        'restore'  => 'بازیابی',
    ],

    // Messages
    'messages'         => [
        'board_created'      => 'تخته با موفقیت ایجاد شد.',
        'board_updated'      => 'تخته با موفقیت به‌روزرسانی شد.',
        'board_deleted'      => 'تخته با موفقیت حذف شد.',
        'card_created'       => 'کارت با موفقیت ایجاد شد.',
        'card_updated'       => 'کارت با موفقیت به‌روزرسانی شد.',
        'card_deleted'       => 'کارت با موفقیت حذف شد.',
        'card_moved'         => 'کارت با موفقیت منتقل شد.',
        'flow_rule_violated' => 'نمی‌توان کارت را منتقل کرد: نقض قانون جریان.',
        'wip_limit_reached'  => 'نمی‌توان کارت اضافه کرد: محدودیت WIP رسیده است.',
        'access_denied'      => 'دسترسی رد شد.',
        'board_not_found'    => 'تخته یافت نشد.',
        'card_not_found'     => 'کارت یافت نشد.',
        'column_not_found'   => 'ستون یافت نشد.',
        'no_board_selected'  => 'لطفاً یک تخته انتخاب کنید.',
        'no_boards_found'    => 'هیچ تخته‌ای مطابق با معیارهای شما یافت نشد.',
    ],

    // Labels
    'labels'           => [
        'board'            => 'تخته',
        'boards'           => 'تخته‌ها',
        'column'           => 'ستون',
        'columns'          => 'ستون‌ها',
        'card'             => 'کارت',
        'cards'            => 'کارت‌ها',
        'flow'             => 'جریان',
        'flows'            => 'جریان‌ها',
        'history'          => 'تاریخچه',
        'name'             => 'نام',
        'description'      => 'توضیحات',
        'color'            => 'رنگ',
        'order'            => 'ترتیب',
        'wip_limit'        => 'محدودیت WIP',
        'due_date'         => 'تاریخ سررسید',
        'priority'         => 'اولویت',
        'status'           => 'وضعیت',
        'type'             => 'نوع',
        'assignees'        => 'مسئولان',
        'reviewers'        => 'بازبینان',
        'watchers'         => 'ناظران',
        'created_at'       => 'تاریخ ایجاد',
        'updated_at'       => 'تاریخ به‌روزرسانی',
        'actions'          => 'عملیات',
        'conditions'       => 'شرایط',
        'from_column'      => 'از ستون',
        'to_column'        => 'به ستون',
        'is_active'        => 'فعال',
        'is_overdue'       => 'معوق',
        'days_until_due'   => 'روز تا سررسید',
        'extra_attributes' => 'ویژگی‌های اضافی',
        'no_board'         => 'بدون تخته',
        'no_boards'        => 'بدون تخته‌ها',
    ],

    // Placeholders
    'placeholders'     => [
        'board_name'         => 'نام تخته را وارد کنید...',
        'board_description'  => 'توضیحات تخته را وارد کنید...',
        'column_name'        => 'نام ستون را وارد کنید...',
        'column_description' => 'توضیحات ستون را وارد کنید...',
        'card_title'         => 'عنوان کارت را وارد کنید...',
        'card_description'   => 'توضیحات کارت را وارد کنید...',
        'search_cards'       => 'جستجو در کارت‌ها...',
        'search_boards'      => 'جستجو در تخته‌ها...',
    ],

    // Tooltips
    'tooltips'         => [
        'add_board'     => 'افزودن تخته جدید',
        'add_column'    => 'افزودن ستون جدید',
        'add_card'      => 'افزودن کارت جدید',
        'edit_board'    => 'ویرایش تخته',
        'edit_column'   => 'ویرایش ستون',
        'edit_card'     => 'ویرایش کارت',
        'delete_board'  => 'حذف تخته',
        'delete_column' => 'حذف ستون',
        'delete_card'   => 'حذف کارت',
        'move_card'     => 'انتقال کارت',
        'assign_card'   => 'تخصیص کارت',
        'archive_card'  => 'بایگانی کارت',
        'restore_card'  => 'بازیابی کارت',
        'view_history'  => 'مشاهده تاریخچه',
        'view_details'  => 'مشاهده جزئیات',
    ],

    // Validation
    'validation'       => [
        'board_name_required'  => 'نام تخته الزامی است.',
        'column_name_required' => 'نام ستون الزامی است.',
        'card_title_required'  => 'عنوان کارت الزامی است.',
        'invalid_color'        => 'فرمت رنگ نامعتبر است.',
        'invalid_date'         => 'فرمت تاریخ نامعتبر است.',
        'wip_limit_positive'   => 'محدودیت WIP باید عدد مثبت باشد.',
        'order_positive'       => 'ترتیب باید عدد مثبت باشد.',
    ],

    // Conditions
    'conditions'       => [
        'equals'                => 'مساوی',
        'not_equals'            => 'نابرابر',
        'contains'              => 'شامل',
        'greater_than'          => 'بزرگتر از',
        'less_than'             => 'کوچکتر از',
        'greater_than_or_equal' => 'بزرگتر یا مساوی',
        'less_than_or_equal'    => 'کوچکتر یا مساوی',
        'before'                => 'قبل از',
        'after'                 => 'بعد از',
        'on'                    => 'در',
        'before_or_on'          => 'قبل یا در',
        'after_or_on'           => 'بعد یا در',
        'is_null'               => 'خالی است',
        'is_not_null'           => 'خالی نیست',
    ],

    // Extra Attributes (for different card types)
    'extra_attributes' => [
        'call'    => [
            'phone_number'  => 'شماره تلفن',
            'call_duration' => 'مدت تماس',
            'call_notes'    => 'یادداشت‌های تماس',
        ],
        'meeting' => [
            'meeting_time'     => 'زمان جلسه',
            'meeting_duration' => 'مدت جلسه',
            'meeting_location' => 'محل جلسه',
            'attendees'        => 'شرکت‌کنندگان',
        ],
        'email'   => [
            'email_subject'    => 'موضوع ایمیل',
            'email_recipients' => 'گیرندگان ایمیل',
            'email_content'    => 'محتوای ایمیل',
        ],
    ],
];
