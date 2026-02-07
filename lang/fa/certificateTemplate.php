<?php

declare(strict_types=1);

return [
    'model' => 'قالب گواهینامه',
    'layout' => [
        'classic' => 'کلاسیک',
        'minimal' => 'مینیمال',
        'custom' => 'سفارشی',
    ],
    'placeholders' => [
        'student_name' => 'نام دانش‌آموز',
        'course_title' => 'عنوان دوره',
        'course_level' => 'سطح دوره',
        'issue_date' => 'تاریخ صدور',
        'grade' => 'نمره',
        'certificate_number' => 'شماره گواهینامه',
        'duration' => 'مدت دوره',
        'institute_name' => 'نام آموزشگاه',
    ],
    'cannot_delete_default' => 'امکان حذف قالب پیش‌فرض گواهینامه وجود ندارد.',
    'download_not_found' => 'فایل گواهینامه یافت نشد.',
    'verification' => 'تأیید گواهینامه',
    'verification_valid' => 'این گواهینامه معتبر است.',
    'verification_invalid' => 'این گواهینامه قابل تأیید نیست.',
    'placeholder_hint' => 'از {{student_name}}، {{course_title}}، {{issue_date}} و غیره استفاده کنید.',
    'page' => [
        'index_title' => 'قالب‌های گواهینامه',
        'create_title' => 'ایجاد قالب گواهینامه',
        'edit_title' => 'ویرایش قالب گواهینامه',
        'title' => 'عنوان',
        'slug' => 'نامک',
        'is_default' => 'قالب پیش‌فرض',
        'layout' => 'چیدمان',
        'header_text' => 'متن هدر',
        'body_text' => 'متن بدنه',
        'footer_text' => 'متن فوتر',
        'institute_name' => 'نام آموزشگاه',
        'logo' => 'لوگو',
        'background' => 'تصویر پس‌زمینه',
        'signature' => 'تصویر امضا',
        'preview' => 'پیش‌نمایش',
    ],
];
