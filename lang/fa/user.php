<?php

declare(strict_types=1);

return [
    'model' => 'زبان آموز',
    'teacher' => 'مربی',
    'employee' => 'کارمند',
    'parent' => 'والدین',
    'user' => 'زبان آموز',

    'validation' => [
        'name_required' => 'وارد کردن نام الزامی است',
        'family_required' => 'وارد کردن نام خانوادگی الزامی است',
        'at_least_one_parent_required' => 'انتخاب حداقل یکی از پدر یا مادر الزامی است.',
    ],

    'exceptions' => [
        'developer_can_not_removed' => 'سوپر ادمین قابل حذف نیست!',
    ],

    'page' => [
        'name_info' => 'شما می توانید حداکثر 255 کاراکتر وارد کنید',
        'family_info' => 'شما می توانید حداکثر 255 کاراکتر وارد کنید',
        'password_info' => 'حداقل 8 کاراکتر',
        'email_info' => 'ایمیل خود را وارد کنید',
        'mobile_info' => 'موبایل خود را وارد کنید',
        'block_info' => 'از این قسمت می توانید این کاربر را مسدود کنید',
        'user_group_info' => 'از این قسمت مشخص می شود که کاربر به چه گروه کاربری تعلق دارد',
        'password_section' => 'از این قسمت می توانید پسورد کاربر را تغییر دهید',
        'parents_info' => 'اطلاعات والدین',
        'salary_info' => 'حقوق ماهیانه',
        'resume_section' => 'رزومه کاری',
        'resume_description' => 'توضیحات رزومه کاری',
        'resume_image' => 'تصویر رزومه کاری',
        'education_section' => 'مدارک تحصیلی',
        'education_description' => 'توضیحات مدارک تحصیلی',
        'education_image' => 'تصویر مدارک تحصیلی',
        'courses_section' => 'دوره‌های گذرانده شده',
        'courses_description' => 'توضیحات دوره‌های گذرانده شده',
        'courses_image' => 'تصویر دوره‌های گذرانده شده',
        'no_image' => 'تصویری انتخاب نشده',
        'images_section' => 'عکس های کاربر',
        'generatin_password_is_mobile_number' => 'پسورد جدید با موبایل خود کاربر برابر خواهد بود',
        'image' => [
            'avatar' => 'عکس پروفایل',
            'national_card' => 'عکس کارت ملیت',
            'birth_certificate' => 'عکس شناسنامه',
            'hint' => 'تصویر های آپلود شده باید 512x512 باشد',
        ],
        'images_gallery' => 'گالری عکس های کاربر',
        'select_father_placeholder' => 'یک پدر را انتخاب کنید',
        'select_mother_placeholder' => 'یک مادر را انتخاب کنید',
        'select_children_placeholder' => 'یک زبان آموز را انتخاب کنید',
        'tabs' => [
            'basic' => 'اطلاعات پایه',
            'images' => 'تصاویر',
            'parents' => 'والدین / فرزندان',
            'salary' => 'حقوق و همکاری',
            'resume' => 'رزومه و مدارک',
            'settings' => 'تنظیمات',
        ],
    ],

    'messages' => [
        'new_password_updated' => 'پسورد جدید با موفقیت آپدیت شد',
        'new_profile_image' => 'عکس پروفایل با موفقیت تغییر کرد',
    ],
    'type_enums' => [
        'teacher' => 'استاد',
        'employee' => 'کارمند',
        'parent' => 'والدین',
        'user' => 'زبان آموز',
    ],
    'profile' => [
        'tabs' => [
            'information-tab' => 'اطلاعات کاربری',
            'settings-tab' => 'تنظیمات',
            'security-tab' => 'امنیت',
            'notifications-tab' => 'اعلانات',
            'tickets-tab' => 'تیکت ها',
            'payments-tab' => 'پرداخت ها',
            'logout-tab' => 'خروج',
        ],
    ],
    'gender' => [
        'male' => 'پسر',
        'female' => 'دختر',
    ],

    'published_status_default_hint' => 'آیا این کاربر اجازه فعالیت در سامانه را خواهد داشت؟',
];
