<?php

declare(strict_types=1);

use App\Enums\BannerSizeEnum;
use App\Enums\CategoryTypeEnum;
use App\Enums\SeoRobotsMetaEnum;
use App\Models\Blog;

return [
    'blogs'        => [
        [
            'title'         => 'شروع سریع با لاراول: راهنمای مبتدیان',
            'description'   => 'مقدمه‌ای عملی بر لاراول برای کسانی که می‌خواهند سریع پروژه‌های خود را با این فریم‌ورک شروع کنند.',
            'body'          => 'لاراول یکی از فریم‌ورک‌های قدرتمند و در عین حال ساده PHP است که برای توسعه سریع وب‌اپلیکیشن‌ها طراحی شده.در این مقاله قدم‌به‌قدم نحوه نصب لاراول، اجرای اولین پروژه و ساخت صفحات ساده را یاد می‌گیریم.مفاهیم پایه مانند روتینگ، کنترلرها، ویوها و مدل‌ها معرفی می‌شن.هدف اینه که بدون نیاز به دانش عمیق قبلی، خیلی سریع وارد دنیای لاراول بشی.از نصب Composer تا اجرای اولین صفحه با Blade، همه چیز رو پوشش می‌دیم.اگه تازه‌کاری، این مقاله یه نقطه شروع عالی برات خواهد بود.',
            'slug'          => 'شروع-سریع-با-لاراول:-راهنمای-مبتدیان',
            'published'     => true,
            'published_at'  => now(),
            'user_id'       => 2,
            'category_id'   => 1,
            'view_count'    => 2,
            'comment_count' => 1,
            'wish_count'    => 2,
            'languages'     => [
                'fa',
            ],
            'seo_options'   => [
                'title'       => 'شروع سریع با لاراول: راهنمای مبتدیان',
                'description' => 'مقدمه‌ای عملی بر لاراول برای کسانی که می‌خواهند سریع پروژه‌های خود را با این فریم‌ورک شروع کنند.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'          => public_path('images/test/blogs/laravel.jpg'),
        ],
        [
            'title'         => 'معماری لاراول: نگاهی عمیق به ساختار داخلی فریم‌ورک',
            'description'   => 'ساختار MVC، سرویس کانتینرها، فَسادها و سایر مفاهیم کلیدی را بررسی می‌کنیم تا درک عمیق‌تری از لاراول پیدا کنید.',
            'body'          => '',
            'slug'          => 'معماری-لاراول:-نگاهی-عمیق-به-ساختار-داخلی-فریم‌ورک',
            'published'     => true,
            'published_at'  => now(),
            'user_id'       => 3,
            'category_id'   => 1,
            'view_count'    => 2,
            'comment_count' => 1,
            'wish_count'    => 2,
            'languages'     => [
                'fa',
            ],
            'seo_options'   => [
                'title'       => 'معماری لاراول: نگاهی عمیق به ساختار داخلی فریم‌ورک',
                'description' => 'ساختار MVC، سرویس کانتینرها، فَسادها و سایر مفاهیم کلیدی را بررسی می‌کنیم تا درک عمیق‌تری از لاراول پیدا کنید.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'          => public_path('images/test/blogs/design.jpg'),
        ],
    ],
    'categories'   => [
        [
            'title'       => 'لاراول',
            'slug'        => 'لاراول',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'body'        => 'این فریم‌ورک با هدف ساده‌سازی توسعه برنامه‌های تحت وب ایجاد شده و امکاناتی مثل مسیریابی ساده، مدیریت پایگاه‌داده با Eloquent ORM، سیستم صف، احراز هویت، ارسال ایمیل، و قالب Blade رو در اختیار توسعه‌دهنده قرار می‌ده.لاراول با سینتکس زیبا و ابزارهای حرفه‌ای، توسعه پروژه‌های کوچک تا بزرگ رو سریع‌تر و لذت‌بخش‌تر می‌کنه.',
            'seo_options' => [
                'title'       => 'لاراول',
                'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type'        => CategoryTypeEnum::BLOG->value,
            'ordering'    => 1,
            'parent_id'   => null,
            'created_at'  => now(),
            'updated_at'  => now(),
            'languages'   => [
                'fa',
            ],
            'path'        => public_path('images/test/categories/laravel-cat.png'),
        ],
    ],
    'faq'          => [
        [
            'title'       => 'faqclscls',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'published'   => true,
            'favorite'    => true,
            'ordering'    => 1,
            'created_at'  => now(),
            'updated_at'  => now(),
            'languages'   => [
                'fa',
            ],
        ],
    ],
    'banner'       => [
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
    'slider'       => [
        [
            'title'       => 'slider test',
            'description' => 'slider description about somethings',
            'published'   => true,
            'ordering'    => 1,
            'link'        => 'https://www.google.com',
            'position'    => App\Enums\SliderPositionEnum::TOP->value,
            'languages'   => [
                'fa',
            ],
            'path'        => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'comment'      => [
        [
            'published'      => true,
            'user_id'        => App\Models\User::query()->first()->id,
            'morphable_id'   => 1,
            'morphable_type' => Blog::class,
            'comment'        => 'this blog not so useful for me',
            'languages'      => [
                'fa',
            ],
        ],
    ],
    'opinion'      => [
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
    'client'       => [
        [
            'title'     => 'karnoweb',
            'languages' => [
                'fa',
            ],
            'path'      => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'teammate'     => [
        [
            'title'       => 'ahmad dehestani',
            'description' => 'something about me',
            'birthday'    => now(),
            'position'    => 'backend',
            'languages'   => [
                'fa',
            ],
            'path'        => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'contact_us'   => [
        [
            'name'    => 'ahmad dehestani',
            'email'   => 'gmail@gmail.com',
            'mobile'  => '09158598300',
            'comment' => 'backend',
        ],
    ],
    'social_media' => [
        [
            'title'     => 'linkedIn',
            'link'      => 'linkedin.com',
            'ordering'  => '1',
            'position'  => App\Enums\SocialMediaPositionEnum::HEADER->value,
            'languages' => [
                'fa',
            ],
            'path'      => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
];
