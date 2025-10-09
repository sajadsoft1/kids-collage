<?php

declare(strict_types=1);

return [
    'model'                         => 'سفارش',
    'permissions'                   => [
    ],
    'exceptions'                    => [
        'items_required'          => 'لطفا آیتم های سفارش را انتخاب کنید',
        'payments_required'       => 'لطفا پرداخت های سفارش را انتخاب کنید',
        'user_id_required'        => 'شناسه کاربر الزامی است',
        'user_not_found'          => 'کاربر یافت نشد',
        'order_id_required'       => 'شناسه سفارش الزامی است',
        'order_not_found'         => 'سفارش یافت نشد',
        'user_already_enrolled'   => 'کاربر قبلا در دوره :course ثبت نام کرده است',
        'payments_total_mismatch' => 'مجموع پرداخت ها (:received) با مبلغ کل سفارش (:expected) مطابقت ندارد',
        'course_full'             => 'ظرفیت دوره :course تکمیل شده است',
    ],
    'validations'                   => [
    ],
    'enum'                          => [
        'type'   => [
            'course' => 'دوره',
        ],
        'status' => [
            'pending'    => 'در انتظار تکمیل پرداخت',
            'processing' => 'درحال پرداخت',
            'completed'  => 'پرداخت شده',
            'cancelled'  => 'لغو شده',
        ],
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
        'add_payment'                                     => 'افزودن پرداخت',
    ],
    'discount_code_required'        => 'لطفا کد تخفیف را وارد کنید',
    'please_select_user_and_course' => 'لطفا ابتدا کاربر و دوره را انتخاب کنید',
    'discount_not_found'            => 'کد تخفیف وارد شده معتبر نیست',
    'course_has_no_price'           => 'دوره انتخابی قیمت ندارد',
    'discount_applied_successfully' => 'کد تخفیف با موفقیت اعمال شد. مبلغ تخفیف: :amount تومان',
];
