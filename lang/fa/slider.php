<?php

declare(strict_types=1);

return [
    'model' => 'اسلایدر',
    'permissions' => [
    ],
    'exceptions' => [
        'published_at_after_now' => 'زمان انتشار نمی تواند قبل از زمان فعلی باشد.',
    ],
    'validations' => [
    ],
    'enum' => [
        'type' => [
            'product' => 'محصول',
            'brand' => 'برند',
            'category' => 'دسته بندی',
            'discount' => 'تخفیف',
            'link' => 'لینک',
            'filter' => 'فیلتر',
            'tag' => 'تگ',
        ],
        'position' => [
            'top' => 'بالای صفحه',
            'middle' => 'وسط صفحه',
            'bottom' => 'پایین صفحه',
        ],
    ],
];
