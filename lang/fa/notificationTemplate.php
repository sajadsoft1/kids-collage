<?php

declare(strict_types=1);

return [
    'model' => 'قالب اعلان',
    'fields' => [
        'event' => 'رویداد',
        'channel' => 'کانال',
        'locale' => 'زبان',
        'name' => 'عنوان قالب',
        'icon' => 'آیکون',
        'subject' => 'موضوع',
        'title' => 'عنوان',
        'subtitle' => 'توضیح کوتاه',
        'body' => 'متن پیام',
        'placeholders' => 'متغیرها',
        'cta_label' => 'متن دکمه',
        'cta_url' => 'لینک دکمه',
        'is_active' => 'فعال',
        'available_variables' => 'متغیرهای در دسترس',
    ],
    'hints' => [
        'icon' => 'آیکون را به صورت کلاس Lucide یا Heroicon وارد کنید (مثلاً o-bell).',
        'subject' => 'برای ایمیل استفاده می‌شود. در سایر کانال‌ها اختیاری است.',
        'placeholders' => 'لیست متغیرهایی که در متن استفاده می‌کنید. بدون آکولاد وارد کنید (مثلاً user_name).',
    ],
    'examples' => [
        'body_hint' => 'Hello {user_name}, your request has been processed successfully.',
    ],
    'messages' => [
        'duplicate_event_channel_locale' => 'برای این ترکیب رویداد، کانال و زبان قبلاً قالبی ثبت شده است.',
    ],
];
