<?php

declare(strict_types=1);

return [
    'model'         => 'دسته بندی',
    'permissions'   => [
    ],
    'exceptions'    => [
        'not_allowed_to_delete_category_due_to_blogs' => 'از این دسته بندی در مقالات استفاده شده است و نمی توانید آنرا حذف کنید',
        'not_allowed_to_delete_category_due_to_faqs'  => 'از این دسته بندی در سوالات پرتکرار استفاده شده است و نمی توانید آنرا حذف کنید',
    ],
    'validations'   => [
    ],
    'enum'          => [
        'type' => [
            'blog'      => 'مقاله',
            'portfolio' => 'نمونه کار',
            'faq'       => 'سوال پرتکرار',
        ],
    ],
    'notifications' => [
    ],
    'page'          => [
    ],
];
