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
    'page' => [
        'subject_info' => 'درباره چه موضوعی می خواهید تیکت ثبت کنید',
        'department_info' => 'دپارتمان مسئول را انتخاب کنید تا تیکت شما به درستی ارجاع داده شود',
        'priority_info' => 'با انتخاب اولویت مناسب، به ما کمک کنید تا درخواست شما را به سرعت بررسی کنیم',
        'status_info' => 'از این قسمت می توانید آخرین وضعیت تیکت را تغییر دهید',
        'ticket_information' => 'اطلاعات تیکت',
        'ticket_status_is_close' => 'وضعیت تیکت  بسته است',
        'ticket_status_change_to_close' => 'بستن تیکت',
        'ticket_status_change_to_open' => 'باز کردن تیکت',
        'ticket_details' => 'جزئیات تیکت',
        'new_ticket' => 'تیکت جدید',
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
