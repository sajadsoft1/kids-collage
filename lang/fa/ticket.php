<?php

declare(strict_types=1);

return [
    'model' => 'تیکت',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
        'status' => [
            'open' => 'باز',
            'close' => 'بسته',
        ],
        'department' => [
            'all' => 'همه موارد',
            'finance_and_administration' => 'مالی و اداری',
            'Sale' => 'فروش',
            'technical' => 'پشتیبانی فنی',
        ],
        'priority' => [
            'all' => 'همه موارد',
            'critical' => 'فوری',
            'high' => 'بالا',
            'medium' => 'متوسط',
            'low' => 'پایین',
        ],
    ],
    'not_found' => 'تیکت یافت نشد',
    'unauthorized' => 'شما مجاز به انجام این عملیات نیستید',
    'cannot_message_closed' => 'امکان ارسال پیام به تیکت بسته شده وجود ندارد',
    'message_sent' => 'پیام با موفقیت ارسال شد',
    'page' => [
        'subject_info' => 'درباره چه موضوعی می خواهید تیکت ثبت کنید',
        'department_info' => 'دپارتمان مسئول را انتخاب کنید تا تیکت شما به درستی ارجاع داده شود',
        'priority_info' => 'با انتخاب اولویت مناسب، به ما کمک کنید تا درخواست شما را به سرعت بررسی کنیم',
        'status_info' => 'از این قسمت می توانید آخرین وضعیت تیکت را تغییر دهید',
        'ticket_information' => 'اطلاعات تیکت',
        'ticket_status_is_close' => 'وضعیت تیکت  بسته است',
        'ticket_status_change_to_close' => 'بستن تیکت',
        'ticket_status_changed_to_close' => 'تیکت بسته شد',
        'ticket_status_change_to_open' => 'باز کردن تیکت',
        'ticket_status_changed_to_open' => 'تیکت باز شد',
        'ticket_details' => 'جزئیات تیکت',
        'new_ticket' => 'تیکت جدید',
        'select_ticket' => 'لطفا یک تیکت را انتخاب کنید',
        'open_tickets_list' => 'باز کردن لیست تیکت‌ها',
        'message_placeholder' => 'پیام خود را بنویسید...',
    ],
    'table' => [
        'ticket_number' => 'شماره تیکت',
        'subject' => 'موضوع',
        'user' => 'کاربر',
        'priority' => 'اولویت',
        'status' => 'وضعیت',
        'created_at' => 'تاریخ ایجاد',
        'actions' => 'عملیات',
    ],
];
