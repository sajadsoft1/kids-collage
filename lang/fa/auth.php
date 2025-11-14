<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    //    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'name' => 'نام',
    'family' => 'نام خانوادگی',
    'email' => 'ایمیل',
    'password' => 'رمز عبور',
    'password_confirmation' => 'تکرار رمز عبور',
    'set_password_for_your_account' => 'رمز عبور خود را برای حساب کاربری خود تنظیم کنید',
    'remember_me' => 'مرا به خاطر بسپار',
    'login' => 'ورود',
    'confirm' => 'تایید اعتبار',
    'login_for_buy_product' => 'برای خرید وارد شوید',
    'register' => 'ثبت نام',
    'forgot_password' => 'رمز عبور خود را فراموش کرده اید؟',
    'reset_password' => 'بازیابی رمز عبور',
    'send_password_reset_link' => 'ارسال کد بازیابی رمز عبور',

    // Login Page
    'sign_in_to_account' => 'ورود به حساب کاربری',
    'enter_your_credentials' => 'اطلاعات خود را برای دسترسی به حساب کاربری وارد کنید',
    'login_successful' => 'با موفقیت وارد شدید!',
    'invalid_credentials' => 'ایمیل یا رمز عبور نامعتبر است.',
    'continue_with_google' => 'ادامه با گوگل',
    'dont_have_account' => 'حساب کاربری ندارید؟',
    'create_account' => 'ایجاد حساب کاربری',
    'by_signing_in' => 'با ورود، شما موافقت می‌کنید با',
    'terms_of_service' => 'شرایط استفاده',
    'privacy_policy' => 'حریم خصوصی',

    // Register Page
    'create_new_account' => 'ایجاد حساب کاربری جدید',
    'fill_information_below' => 'اطلاعات زیر را برای ایجاد حساب کاربری خود پر کنید',
    'registration_successful' => 'حساب کاربری با موفقیت ایجاد شد!',
    'password_confirmation' => 'تکرار رمز عبور',
    'i_agree_to_terms' => 'من با شرایط و قوانین موافقت می‌کنم',
    'already_have_account' => 'قبلاً حساب کاربری دارید؟',
    'sign_in' => 'ورود',
    'by_creating_account' => 'با ایجاد حساب کاربری، شما موافقت می‌کنید با',

    // Forget Password Page
    'enter_email_to_reset' => 'ایمیل خود را برای بازیابی رمز عبور وارد کنید',
    'send_reset_link' => 'ارسال لینک بازیابی',
    'reset_link_sent' => 'لینک بازیابی ارسال شد!',
    'check_your_email' => 'ایمیل خود را برای دستورالعمل‌های بازیابی رمز عبور بررسی کنید',
    'reset_link_instructions' => 'ما یک لینک بازیابی رمز عبور به ایمیل شما ارسال کرده‌ایم. لطفاً صندوق ورودی خود را بررسی کرده و دستورالعمل‌ها را دنبال کنید.',
    'back_to_login' => 'بازگشت به ورود',
    'need_help' => 'نیاز به کمک دارید؟',
    'contact_support' => 'تماس با پشتیبانی',

    // Confirm Page
    'verify_your_email' => 'ایمیل خود را تایید کنید',
    'enter_verification_code' => 'کد تایید ارسال شده به ایمیل خود را وارد کنید',
    'verification_code' => 'کد تایید',
    'code_sent_to_email' => 'یک کد 6 رقمی به ایمیل شما ارسال شده است',
    'verify_email' => 'تایید ایمیل',
    'didnt_receive_code' => 'کد را دریافت نکردید؟',
    'resend_code' => 'ارسال مجدد کد',
    'email_verified_successfully' => 'ایمیل با موفقیت تایید شد!',
    'account_activated' => 'حساب کاربری شما فعال شده است',
    'you_can_now_login' => 'اکنون می‌توانید وارد حساب کاربری خود شوید',
    'need_help_verifying' => 'نیاز به کمک برای تایید ایمیل دارید؟',
    'demo_code_notice' => 'کد تایید آزمایشی',

    // Validation Messages
    'email_required' => 'آدرس ایمیل الزامی است.',
    'email_invalid' => 'لطفاً یک آدرس ایمیل معتبر وارد کنید.',
    'email_not_found' => 'هیچ حسابی با این ایمیل یافت نشد.',
    'email_already_exists' => 'حسابی با این ایمیل قبلاً وجود دارد.',
    'password_required' => 'رمز عبور الزامی است.',
    'password_min' => 'رمز عبور باید حداقل 8 کاراکتر باشد.',
    'password_confirmation_mismatch' => 'تکرار رمز عبور مطابقت ندارد.',
    'password_confirmation_required' => 'تکرار رمز عبور الزامی است.',
    'name_required' => 'نام الزامی است.',
    'name_min' => 'نام باید حداقل 2 کاراکتر باشد.',
    'family_required' => 'نام خانوادگی الزامی است.',
    'family_min' => 'نام خانوادگی باید حداقل 2 کاراکتر باشد.',
    'terms_required' => 'شما باید با شرایط و قوانین موافقت کنید.',
    'terms_must_be_accepted' => 'شما باید با شرایط و قوانین موافقت کنید.',
    'code_required' => 'کد تایید الزامی است.',
    'code_size' => 'کد تایید باید 6 رقم باشد.',
    'invalid_code' => 'کد تایید نامعتبر است.',
    'user_not_found' => 'کاربر یافت نشد.',
    'reset_link_error' => 'نمی‌توان لینک بازیابی را ارسال کرد. لطفاً دوباره تلاش کنید.',
    'code_resent' => 'کد تایید مجدداً ارسال شد.',

    // Common
    'or_continue_with' => 'یا ادامه با',
];
