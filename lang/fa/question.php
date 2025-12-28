<?php

declare(strict_types=1);

return [
    'model' => 'سوال',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
    ],
    'notifications' => [
    ],
    'page' => [
        'data' => 'اطلاعات پایه',
        'basic_info' => 'اطلاعات پایه',
        'content' => 'محتوای سوال',
        'options' => 'گزینه‌ها و تنظیمات',
        'tags' => 'برچسب‌ها',
        'title' => 'عنوان سوال',
        'body' => 'متن اضافی',
        'explanation' => 'توضیحات پاسخ',
        'submit' => 'ذخیره تغییرات',
        'create' => 'ایجاد سوال',
        'content_not_available' => 'محتوای سوال برای این نوع سوال موجود نیست',
        'content_not_available_description' => 'محتوای سوال برای این نوع سوال موجود نیست',
        'please_select_type' => 'لطفا نوع سوال را انتخاب کنید',
        'please_select_type_description' => 'لطفا نوع سوال را انتخاب کنید تا محتوای سوال موجود شود',
    ],
    'builder' => [
        'common' => [
            'settings' => 'تنظیمات',
            'shuffle_options' => 'به هم ریختن گزینه‌ها هنگام نمایش',
        ],
        'multiple' => [
            'add_option' => 'افزودن گزینه',
            'shuffle_options' => 'به هم ریختن گزینه‌ها هنگام نمایش',
            'scoring_type' => 'نوع نمره‌دهی',
            'scoring_all_or_nothing' => 'همه یا هیچ (باید همه پاسخ‌های صحیح انتخاب شوند)',
            'scoring_partial' => 'نمره جزئی (بر اساس تعداد پاسخ‌های صحیح)',
        ],
        'single' => [
            'add_option' => 'افزودن گزینه',
            'show_explanation' => 'نمایش توضیحات پس از پاسخ',
        ],
        'true_false' => [
            'true_label' => 'برچسب درست',
            'false_label' => 'برچسب غلط',
            'correct_answer' => 'پاسخ صحیح',
            'shuffle_info' => 'چیدمان پاسخ‌ها به صورت تصادفی تغییر می‌کند',
        ],
    ],
    'display' => [
        'multiple' => [
            'hint_multi_select' => 'می‌توانید چند گزینه را انتخاب کنید',
        ],
        'correct' => 'پاسخ صحیح',
        'should_not_be_selected' => 'نباید انتخاب می‌شد',
        'explanation' => 'توضیحات',
    ],
    'info' => [
        'select_one_correct' => 'حداقل یک گزینه را به عنوان پاسخ صحیح انتخاب کنید.',
    ],
];
