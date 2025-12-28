<?php

declare(strict_types=1);

return [
    'model' => 'Category',
    'permissions' => [
    ],
    'exceptions' => [
        'not_allowed_to_delete_category_due_to_blogs' => 'This category is used for articles and you cannot delete it.',
        'not_allowed_to_delete_category_due_to_faqs' => 'This category is used for frequently asked questions and you cannot delete it.',
    ],
    'validations' => [
    ],
    'enum' => [
        'type' => [
            'blog' => 'Blog',
            'portfolio' => 'Portfolio',
            'faq' => 'FAQ',
            'bulletin' => 'Bulletin',
        ],
    ],
    'notifications' => [
    ],
    'page' => [
    ],
];
