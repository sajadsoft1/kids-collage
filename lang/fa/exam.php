<?php

declare(strict_types=1);

return [
    'model' => 'آزمون',
    'type' => 'نوع',
    'status' => 'وضعیت',
    'total_score' => 'نمره کل',
    'duration' => 'مدت زمان',
    'questions_count' => 'تعداد سوالات',
    'manual_review_count' => 'نیازمند تصحیح دستی',
    'total_weight' => 'مجموع وزن',
    'attempts_count' => 'تعداد تلاش‌ها',
    'duration_label' => 'مدت زمان (دقیقه)',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'validation' => [
        'total_weight_mismatch' => 'مجموع وزن سوالات با نمره کل برابر نیست. لطفاً وزن سوالات را تنظیم کنید.',
    ],
    'enum' => [
    ],
    'notifications' => [
    ],
    'form' => [
        'basic_tab' => 'اطلاعات پایه',
        'rules_tab' => 'قوانین دسترسی',
        'questions_tab' => 'سوالات',
        'basic_information' => 'اطلاعات عمومی',
        'scoring_settings' => 'تنظیمات نمره و تلاش‌ها',
        'schedule_settings' => 'زمان‌بندی و وضعیت',
        'delivery_settings' => 'تنظیمات نمایش نتیجه',
        'question_overview' => 'مرور سوالات',
        'rules_builder_title' => 'قوانین شرکت در آزمون',
        'no_questions_title' => 'هنوز سوالی متصل نشده است',
        'no_questions_desc' => 'از مدیریت سوالات آزمون برای افزودن سوال و مشاهده آمار استفاده کنید.',
        'questions_manage_title' => 'مدیریت سوالات آزمون',
        'questions_need_save_title' => 'برای افزودن سوال ابتدا آزمون را ذخیره کنید',
        'questions_need_save_desc' => 'لطفاً ابتدا آزمون را ذخیره کرده و سپس سوالات را از بانک انتخاب نمایید.',
    ],
    'page' => [
        'builder' => [
            'group' => [
                'rules' => [
                    'and' => 'و (AND)',
                    'or' => 'یا (OR)',
                ],
            ],
            'remove_group' => 'حذف گروه',
        ],
    ],
];
