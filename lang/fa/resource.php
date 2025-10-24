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
    'page'          => [
    ],
];
