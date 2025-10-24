<?php

declare(strict_types=1);

return [
    'model'         => 'منبع',
    'type'          => 'نوع منبع',
    'attached_to'   => 'متصل به',
    'url'           => 'آدرس اینترنتی',
    'file'          => 'فایل',
    'order'         => 'ترتیب نمایش',
    'is_public'     => 'عمومی',
    'description'   => 'توضیحات',
    'permissions'   => [
    ],
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
        'pdf'   => 'فایل PDF',
        'video' => 'ویدیو',
        'image' => 'تصویر',
        'audio' => 'صوت',
        'file'  => 'فایل',
        'link'  => 'لینک',
    ],
    'notifications' => [
    ],
    'page'               => [
        'attached_resources'                 => 'منابع متصل',
        'attached_resources_description'     => 'منابع متصل به این منبع',
        'add_resource'                       => 'افزودن منبع',
        'no_resources'                       => 'هیچ منبعی وجود ندارد',
        'visibility'                         => 'وضعیت نمایش',
        'public'                             => 'عمومی',
        'private'                            => 'خصوصی',
        'file_size'                          => 'حجم فایل',
        'created_at'                         => 'تاریخ ایجاد',
        'updated_at'                         => 'تاریخ بروزرسانی',
        'add_relationship'                   => 'افزودن رابطه',
        'no_relationships'                   => 'هیچ رابطه‌ای تعریف نشده است',
        'click_add_to_create'                => 'برای ایجاد رابطه جدید روی دکمه افزودن کلیک کنید',
    ],
    'attached_resources' => 'منابع متصل',
    'add_relationship'   => 'افزودن رابطه',
    'no_relationships'   => 'هیچ رابطه‌ای تعریف نشده است',
    'click_add_to_create' => 'برای ایجاد رابطه جدید روی دکمه افزودن کلیک کنید',
];
