<?php

declare(strict_types=1);

use App\Enums\BannerSizeEnum;
use App\Enums\SliderPositionEnum;
use App\Models\Blog;

return [
    'banner'  => [
        [
            'title'       => 'banner',
            'description' => 'banner description about somethings',
            'published'   => true,
            'size'        => BannerSizeEnum::S16X9->value,
            'languages'   => [
                'fa',
            ],
            'path'        => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'slider'  => [
        [
            'title'       => 'slider test',
            'description' => 'slider description about somethings',
            'published'   => true,
            'ordering'    => 1,
            'link'        => 'https://www.google.com',
            'position'    => SliderPositionEnum::TOP->value,
            'languages'   => [
                'fa',
            ],
            'path'        => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'comment' => [
        [
            'published'      => true,
            'user_id'        => App\Models\User::query()->first()->id,
            'morphable_id'   => 1,
            'morphable_type' => Blog::class,
            'comment'        => 'this blog not so useful for me',
        ],
    ],
    'opinion' => [
        [
            'published' => true,
            'ordering'  => 1,
            'company'   => 'my company',
            'user_name' => 'test user',
            'comment'   => 'this is sample test.',
            'languages' => [
                'fa',
            ],
            'path'      => public_path('images/test/categories/laravel-cat.png'),
        ],
    ],
];
