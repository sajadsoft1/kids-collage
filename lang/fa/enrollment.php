<?php

declare(strict_types=1);

return [
    'model' => 'ثبت نام',
    'issue_certificate' => 'صدور گواهینامه',
    'cannot_issue_certificate' => 'امکان صدور گواهینامه برای این ثبت‌نام وجود ندارد.',
    'certificate_issued_success' => 'گواهینامه با موفقیت صادر شد.',
    'not_found' => 'ثبت‌نام یافت نشد.',
    'invalid_grade' => 'لطفاً یک نمره معتبر (A تا F) انتخاب کنید.',
    'modal_issue_certificate_title' => 'صدور گواهینامه',
    'modal_student' => 'دانش‌آموز',
    'modal_course' => 'دوره',
    'modal_attendance_percentage' => 'درصد حضور',
    'modal_progress_percent' => 'درصد پیشرفت',
    'modal_exam_results' => 'نتایج آزمون‌های انجام‌شده توسط این کاربر',
    'modal_no_exam_results' => 'هیچ آزمون تکمیل‌شده‌ای ثبت نشده است.',
    'modal_linked_to_course' => 'مرتبط با این دوره',
    'modal_score' => 'نمره',
    'modal_exam_unknown' => 'آزمون نامشخص',
    'modal_passed' => 'قبول',
    'modal_failed' => 'رد',
    'modal_not_scored' => 'بدون نمره',
    'modal_suggested_grade' => 'نمره پیشنهادی (خودکار)',
    'modal_final_grade_label' => 'نمره نهایی (توسط ادمین)',
    'modal_confirm_issue' => 'صدور گواهینامه',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
        'status' => [
            'pending' => 'در انتظار',
            'paid' => 'پرداخت شده',
            'active' => 'فعال',
            'dropped' => 'لغو شده',
        ],
    ],
    'notifications' => [
    ],
    'page' => [
        'no_students' => 'هیچ دانش‌آموزی در این دوره ثبت‌نام نشده است',
    ],
];
