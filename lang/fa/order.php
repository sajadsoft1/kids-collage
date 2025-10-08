<?php

declare(strict_types=1);

return [
    'model'                         => 'سفارش',
    'permissions'                   => [
    ],
    'exceptions'                    => [
    ],
    'validations'                   => [
    ],
    'enum'                          => [
    ],
    'notifications'                 => [
    ],
    'page'                          => [
        'tracking_code'                                   => 'کد رهگیری',
        'last_card_digits'                                => 'چهار رقم آخر کارت',
        'payment_note'                                    => 'یادداشت پرداخت',
        'order_note'                                      => 'یادداشت سفارش',
        'payment_schedule'                                => 'برنامه پرداخت',
        'please_first_select_user_and_course_description' => 'لطفا ابتدا کاربر و دوره را انتخاب کنید تا پرداخت ها را ثبت کنید',
        'amount_to_pay'                                   => 'مبلغ پرداختی',
    ],
    'discount_code_required'        => 'لطفا کد تخفیف را وارد کنید',
    'please_select_user_and_course' => 'لطفا ابتدا کاربر و دوره را انتخاب کنید',
    'discount_not_found'            => 'کد تخفیف وارد شده معتبر نیست',
    'course_has_no_price'           => 'دوره انتخابی قیمت ندارد',
    'discount_applied_successfully' => 'کد تخفیف با موفقیت اعمال شد. مبلغ تخفیف: :amount تومان',
];
