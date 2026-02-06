<?php

declare(strict_types=1);

return [
    'model' => 'حضور و غیاب',
    'permissions' => [
    ],
    'exceptions' => [
        'unauthorized_action' => 'شما مجوز انجام این عملیات را ندارید',
        'attendance_must_be_marked_first' => 'ابتدا باید حضور دانش‌آموز را ثبت کنید',
        'leave_time_only_for_present' => 'فقط برای دانش‌آموزان حاضر می‌توان زمان خروج ثبت کرد',
        'select_at_least_one_student' => 'لطفاً حداقل یک دانش‌آموز را انتخاب کنید',
    ],
    'validations' => [
    ],
    'enum' => [
    ],
    'notifications' => [
        'attendance_marked_successfully' => 'حضور و غیاب با موفقیت ثبت شد',
        'leave_time_recorded_successfully' => 'زمان خروج با موفقیت ثبت شد',
        'students_marked_present' => ':count دانش‌آموز به عنوان حاضر ثبت شدند',
        'students_marked_absent' => ':count دانش‌آموز به عنوان غایب ثبت شدند',
        'absence_marked_successfully' => 'غیبت با موفقیت ثبت شد',
    ],
    'page' => [
        'present' => 'حاضر',
        'absent' => 'غایب',
        'all_present' => 'همه حاضر',
        'all_absent' => 'همه غایب',
        'mark_all_present' => 'همه حاضر هستند',
        'mark_all_absent' => 'همه غایب هستند',
        'record_leave_time' => 'ثبت زمان خروج',
        'record_excuse' => 'ثبت دلیل غیبت',
        'excuse_note' => 'دلیل غیبت',
        'excuse_note_placeholder' => 'دلیل غیبت را وارد کنید...',
        'mark_absent' => 'ثبت غیبت',
        'bulk_action_hint' => 'برای تغییر وضعیت چند نفر، از چک باکس‌ها استفاده کنید',
        'my_attendance_status' => 'وضعیت حضور من',
        'status' => 'وضعیت',
        'arrival' => 'ورود',
        'departure' => 'خروج',
        'arrival_time' => 'زمان ورود',
        'delay' => 'تاخیر',
        'minutes' => 'دقیقه',
        'excuse' => 'عذر',
        'attendance_not_recorded' => 'حضور و غیاب شما برای این جلسه ثبت نشده است',
        'by_student' => 'به ازای دانش‌آموز',
        'by_session' => 'به ازای جلسه',
        'all_records' => 'همه رکوردها',
        'total_sessions' => 'تعداد جلسات',
        'present_count' => 'حاضر',
        'attendance_percent' => 'درصد حضور',
        'view_records' => 'مشاهده رکوردها',
        'absent_count' => 'غایب',
        'mark_attendance' => 'ثبت حضور و غیاب',
    ],

    'learning' => [
        'all_records' => [
            'title' => 'همه رکوردهای حضور و غیاب',
            'content' => '<p>لیست تک‌تک رکوردهای حضور و غیاب (هر دانش‌آموز در هر جلسه). می‌توانید با فیلتر دوره و دانش‌آموز جستجو کنید، چند رکورد را با چک‌باکس انتخاب کرده و با دکمه‌های «همه حاضر» یا «همه غایب» وضعیت را یکجا تغییر دهید.</p>',
        ],
        'by_student' => [
            'title' => 'خلاصه به ازای دانش‌آموز',
            'content' => '<p>هر ردیف یک دانش‌آموز (ثبت‌نام) است: تعداد جلسات، تعداد حاضر، درصد حضور. با کلیک روی «مشاهده رکوردها» می‌توانید رکوردهای همان دانش‌آموز را در صفحهٔ همه رکوردها ببینید.</p>',
        ],
        'by_session' => [
            'title' => 'خلاصه به ازای جلسه',
            'content' => '<p>هر ردیف یک جلسهٔ دوره است: دوره، تاریخ و زمان، تعداد حاضر و غایب، درصد حضور. با کلیک روی دکمهٔ «ثبت حضور و غیاب» به صفحهٔ همان دوره می‌روید تا حضور و غیاب آن جلسه را ثبت کنید.</p>',
        ],
    ],
];
