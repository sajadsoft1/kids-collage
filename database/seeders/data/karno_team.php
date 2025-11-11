<?php

declare(strict_types=1);

use App\Enums\SocialMediaPositionEnum;

return [
    'client' => [
        [
            'title' => 'karnoweb',
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'teammate' => [
        [
            'title' => 'ahmad dehestani',
            'description' => 'something about me',
            'birthday' => now(),
            'position' => 'backend',
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'contact_us' => [
        [
            'name' => 'ahmad dehestani',
            'email' => 'gmail@gmail.com',
            'mobile' => '09158598300',
            'comment' => 'backend',
        ],
    ],
    'social_media' => [
        [
            'title' => 'linkedIn',
            'link' => 'linkedin.com',
            'ordering' => '1',
            'position' => SocialMediaPositionEnum::HEADER->value,
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
];
