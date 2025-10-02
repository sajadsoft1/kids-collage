<?php

declare(strict_types=1);

return [
    'model'      => 'کاربر',

    'validation' => [
        'name_required'   => 'وارد کردن نام الزامی است',
        'family_required' => 'وارد کردن نام خانوادگی الزامی است',
    ],

    'exceptions' => [
        'developer_can_not_removed' => 'سوپر ادمین قابل حذف نیست!',
    ],

    'page'       => [
        'name_info'       => 'شما می توانید حداکثر 255 کاراکتر وارد کنید',
        'family_info'     => 'شما می توانید حداکثر 255 کاراکتر وارد کنید',
        'password_info'   => 'حداقل 8 کاراکتر',
        'email_info'      => 'ایمیل خود را وارد کنید',
        'mobile_info'     => 'موبایل خود را وارد کنید',
        'block_info'      => 'از این قسمت می توانید این کاربر را مسدود کنید',
        'user_group_info' => 'از این قسمت مشخص می شود که کاربر به چه گروه کاربری تعلق دارد',
    ],

    'messages'   => [
        'new_password_updated' => 'پسورد جدید با موفقیت آپدیت شد',
        'new_profile_image'    => 'عکس پروفایل با موفقیت تغییر کرد',
    ],
    'type_enums'=>[
        'teacher'=>'استاد',
        'employee'=>'کارمند',
        'parent'=>'والدین',
        'user'=>'کاربر',

    ]
];
