<?php

declare(strict_types=1);

use App\Enums\BannerSizeEnum;
use App\Enums\PageTypeEnum;
use App\Enums\SeoRobotsMetaEnum;
use App\Models\Blog;

return [
    'banner' => [
        [
            'title' => 'banner',
            'description' => 'banner description about somethings',
            'published' => true,
            'size' => BannerSizeEnum::S16X9->value,
            'link' => 'https://www.google.com',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/b1.jpg'),
        ], [
            'title' => 'test',
            'description' => 'test description',
            'published' => true,
            'size' => BannerSizeEnum::S16X9->value,
            'link' => null,
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/b2.jpg'),
        ],
    ],
    'slider' => [
        [
            'title' => 'فراگیری زبان در ۶ ماه',
            'description' => 'میخوای ۶ ماه به راحتی انگلیسی صحبت کنی؟؟',
            'published' => true,
            'ordering' => 1,
            'link' => 'https://www.google.com',
            'position' => App\Enums\SliderPositionEnum::TOP->value,
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/gallery-img-1.jpg'),
        ],
        [
            'title' => 'بهترین سنین فراگیری زبان',
            'description' => 'اگر از کودکی زبان را فرابگیریم بنظرم بهتره',
            'published' => true,
            'ordering' => 2,
            'link' => 'https://www.google.com',
            'position' => App\Enums\SliderPositionEnum::TOP->value,
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/gallery-img-3.jpg'),
        ],
    ],
    'comment' => [
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'this blog not so useful for me',
            'languages' => [
                'fa',
            ],
        ],
    ],
    'client' => [
        [
            'title' => 'اموزش پرورش',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/partner-1.png'),
        ], [
            'title' => 'کارنووب',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/partner-2.png'),
        ], [
            'title' => 'بیمارستان امام رضا',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/partner-3.png'),
        ], [
            'title' => 'یجایی',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/partner-4.png'),
        ], [
            'title' => 'اینجا',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/partner-5.png'),
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
            'position' => App\Enums\SocialMediaPositionEnum::HEADER->value,
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'bulletin' => [
        [
            'title' => 'اطلاعیه مهم',
            'description' => 'توضیحات اطلاعیه مهم',
            'body' => 'متن کامل اطلاعیه مهم',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-2-img-2.jpg'),
        ], [
            'title' => 'اطلاعیه خیللللی  مهممممم مهم',
            'description' => 'توضیحات اطلاعیه خیلییی مهم مهم',
            'body' => 'برنامه زمانبندی ثبت‌نام زبان‌آموزان کانون زبان ایران برای ترم پاییز ۱۴۰۴ اعلام شد.
به گزارش روابط عمومی و امور بین الملل کانون زبان ایران، بر اساس برنامه اعلام شده، شروع ثبت نام اینترنتی از طریق سامانۀ جامع کانون زبان ایران برای زبان آموزان گروه سنی کودک از روز دوشنبه ۷ مهرماه و برای گروه سنی نوجوان از روز سه شنبه ۸ مهرماه خواهد بود.
همچنین ثبت نام اینترنتی برای زبان آموزان گروهای سنی بزرگسال از روز چهارشنبه ۹ مهرماه از طریق سامانۀ جامع کانون زبان ایران میسر خواهد بود.
گفتنی است، در تاریخ های ذکر شده امکان ثبت‌نام فقط برای گروه های سنی اعلام شده امکان پذیر است.
براساس این خبر همۀ گروه‌های سنی از ساعت ۸ صبح روز پنج شنبه ۱۰ مهرماه امکان ثبت‌نام اینترنتی و حضوری را خواهند داشت.

همچنین شروع ترم پاییز کانون زبان ایران از ۱۴ مهرماه خواهد بود.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-2-img-3.jpg'),
        ], [
            'title' => 'اطلاعیه یکم مهم',
            'description' => 'توضیحات اطلاعیه  یکم مهم',
            'body' => ' واقعا هیچ مرز دقیق و مشخصی برای اینکه بشه سطوح برنامه نویس رو جدا کرد وجود نداره ولی میشه گفت برنامه نویس جونیور به فردی گفته می‌شود که در ابتدای مسیر حرفه‌ای خود در حوزه برنامه‌نویسی قرار دارد. برنامه نویس جونیور معمولاً دارای تجربه‌ای کمتر از دو سال بوده و همچنان در حال یادگیری اصول برنامه‌نویسی، ابزارهای توسعه نرم‌افزار و روش‌های حل مسئله است. برنامه‌نویس جونیور معمولاً تحت نظارت توسعه‌دهندگان ارشد کار کرده و مسئولیت‌های ساده‌تری را در پروژه‌ها بر عهده دارن معمولا پروژه های بزرگ رو خودشون نمیتونن به صورت کامل انجام بدن و نیاز به راهنمایی وهمراهی برنامه نویس های با تجره تر دارند.',
            'published' => true,
            'languages' => ['fa'],
            'category_id' => 4,
            'path' => public_path('assets/web/img/news-details-img-1.jpg'),
        ], [
            'title' => 'اطلاعیه تا مقداری  مهم',
            'description' => 'توضیحات اطلاعیه تا مقداری مهم مهم',
            'body' => 'آشنایی با یک یا چند زبان برنامه‌نویسی

برنامه‌نویسان جونیور معمولاً در حال یادگیری زبان‌هایی مانند Python، JavaScript، Java، C++، PHP یا Swift هستند.اونا باید حداقل دانش اولیه‌ای درباره سینتکس، متغیرها، حلقه‌ها، شرط‌ها و توابع داشته باشن. چیزی که تو این قسمت براشون سخت انخاب یک زبان برنامه نویسی که بخوان تو اون عمیق بشن معمولا سر درگم میشن وهم زمان شورع به یادگیری جند زبان رو میکنند.

درک اصول برنامه‌نویسی شیءگرا (OOP)

مفاهیمی مانند کلاس‌ها، اشیا، وراثت و پلی‌مورفیسم برای توسعه نرم‌افزارهای مقیاس‌پذیر بسیار مهم هستند. شاید اولش تو این اصطلاحات گم بشین یا ازش بترسین ولی به مرور زمان میشه بخش لذت بخش کارتون.

آشنایی با Git و سیستم‌های کنترل نسخه

استفاده از Git و GitHub یا GitLab برای مدیریت کد، مشارکت در پروژه‌های گروهی و بررسی تغییرات ضروری است. اصلا مگه میشه تو دنیای الان برنامه نویس باشی و از این تکنولوژی ها استفاده نکنی. خیلی مهمه که بتونی کدهاتو با همکارات به اشتراک بزاری تو پروژه واقعی.

تسلط نسبی بر HTML، CSS و JavaScript

در صورتی که قصد فعالیت در توسعه وب را دارند، باید این فناوری‌ها را به خوبی بشناسند. اینا پایه طراحی وب هستن و تمام برنامه نویسان وب از این html شروع میکنن چون یک زبان نشانه گزاری هست که به زبان انسان هم نزدیک و سینتکس ساده ای داره.

توانایی کار با پایگاه داده

آشنایی اولیه با SQL و NoSQL مانند MySQL، PostgreSQL یا MongoDB برای ذخیره و بازیابی داده‌ها ضروری است. البته اگه قصد داری تو زمینه بک اند فعالیت داری باید پایگاه داده رو یاد بگیری اگه فقط بخوای برنامه نویس فرانت باشی اضلا نیاز نیست اینارو یاد بگیری.

یادگیری و تطبیق‌پذیری سریع

برنامه‌نویسان جونیور باید بتوانند با فناوری‌های جدید، فریم‌ورک‌ها و ابزارهای جدید کار کنند. واقعا این یک اصل که باید همیشه به روز باشی وحتما تو زیمنه کاری خودت بروز باشی تا بتونی موفق باشی راحت تر از سطح جونیور به سطح بعدی بری

مهارت در حل مسئله و دیباگ کردن کد

توانایی شناسایی و رفع باگ‌ها از مهارت‌های کلیدی هر برنامه‌نویس است اما وقتی برنامه نویس جونیور هستی تعداد باگ هات بیشتر میشه چون داری همه چیز رو تازه تجربه میکنی.  به مرور با باگ های کمتری برخورد میکنید و تواناییتون تو رفع باگ های جدیدم بیشتر میشه. اصلا از باگ نترسید چون هیجی مثل باگ باعث رشد شما نخوتهد شد.

توانایی کار تیمی و ارتباط مؤثر

برنامه‌نویسان جونیور معمولاً تحت نظارت توسعه‌دهندگان ارشد کار می‌کنند، بنابراین مهارت برقراری ارتباط و پرسیدن سوالات مناسب بسیار مهم است. این خیلی مهمه که شما بتونید با هم تیمی های خودتون ارتباط خوب برقرار کنید. حتما دقت کنید قبا زا سوال از یزنامه نویسان سطح بالاتر سرج متید وتلاش کنید مشکل ور خودتون حل کنید و زیاد سوال های ابتدایی نپرسید چون دیگه همکارارو خسته میکنید ولی در کل از سوال کردن نترسید چون بالاخره قوی ترین برنامه نویس تیمتونم خودش یه روز برنامه نویس جونیور بوده و این دوران رو تجربه کرده.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-2-img-2.jpg'),
        ],
    ],
    'license' => [
        [
            'title' => 'مجوز آموزشی',
            'description' => 'توضیحات مجوز آموزشی',
            'published' => true,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/gallery-img-4.jpg'),
        ],
        [
            'title' => '2مجوز آموزشی',
            'description' => 'توضیحات مجوز آموزشی2323',
            'published' => true,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/gallery-img-3.jpg'),
        ],
        [
            'title' => '2مجوز آموزشی',
            'description' => 'توضیحات مجوز آموزشی2323',
            'slug' => 'test-licensesss',
            'published' => true,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/gallery-img-1.jpg'),
        ],
        [
            'title' => '2مجوز آموزشی',
            'description' => 'توضیحات مجوز آموزشی2323',
            'slug' => 'test-licensesss-89',
            'published' => true,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/gallery-img-2.jpg'),
        ],
    ],
    'room' => [
        [
            'title' => 'کلاس A',
            'description' => 'توضیحات کلاس A',
            'capacity' => 30,
            'languages' => ['fa'],
            'path' => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'course' => [
        [
            'title' => 'دوره لاراول',
            'description' => 'توضیحات دوره لاراول',
            'body' => 'محتوای کامل دوره لاراول',
            'teacher_id' => 2,
            'category_id' => 1,
            'price' => 1000000,
            'type' => 'in-person',
            'start_date' => '2025-01-01',
            'end_date' => '2025-03-01',
            'languages' => ['fa'],
            'path' => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'session' => [
        [
            'title' => 'جلسه اول',
            'description' => 'توضیحات جلسه اول',
            'body' => 'محتوای جلسه اول',
            'course_id' => 1,
            'teacher_id' => 2,
            'start_time' => '2025-01-01 10:00:00',
            'end_time' => '2025-01-01 12:00:00',
            'room_id' => 1,
            'meeting_link' => 'https://meet.example.com',
            'session_number' => 1,
            'languages' => ['fa'],
            'path' => public_path('images/test/blogs/laravel.jpg'),
        ],
    ],
    'order' => [
        [
            'user_id' => 2,
            'total_amount' => 1000000,
            'status' => 'pending',
        ],
    ],
    'payment' => [
        [
            'user_id' => 2,
            'order_id' => 1,
            'amount' => 1000000,
            'type' => 'full_online',
            'status' => 'pending',
            'transaction_id' => 'TXN123456',
        ],
    ],
    'installment' => [
        [
            'payment_id' => 1,
            'amount' => 500000,
            'due_date' => '2025-02-01',
            'method' => 'online',
            'status' => 'pending',
            'transaction_id' => null,
        ],
    ],
    'enrollment' => [
        [
            'user_id' => 2,
            'course_id' => 1,
            'enroll_date' => '2025-01-01',
            'status' => 'active',
        ],
    ],
    'attendance' => [
        [
            'enrollment_id' => 1,
            'session_id' => 1,
            'present' => true,
            'arrival_time' => '2025-01-01 09:55:00',
            'leave_time' => '2025-01-01 12:05:00',
        ],
    ],
    'about_us' => [
        [
            'title' => 'درباره ما',
            'body' => 'ما یک آموزشگاه زبان هستیم که با هدف ارتقاء مهارت‌های زبانی افراد در محیطی دوستانه و حرفه‌ای فعالیت می‌کنیم. تیم ما متشکل از اساتید مجرب و متعهد است که با استفاده از روش‌های نوین آموزشی، به زبان‌آموزان کمک می‌کنند تا به اهداف زبانی خود دست یابند. ما به کیفیت آموزش، رضایت زبان‌آموزان و ایجاد تجربه‌ای مثبت در فرآیند یادگیری اهمیت می‌دهیم.',
            'slug' => 'about-us',
            'deletable' => App\Enums\YesNoEnum::NO->value,
            'type' => PageTypeEnum::ABOUT_US->value,
            'languages' => [
                'fa',
            ],
            'seo_options' => [
                'title' => 'درباره ما',
                'description' => 'ما یک آموزشگاه زبان هستیم که با هدف ارتقاء مهارت‌های زبانی افراد در محیطی دوستانه و حرفه‌ای فعالیت می‌کنیم. تیم ما متشکل از اساتید مجرب و متعهد است که با استفاده از روش‌های نوین آموزشی، به زبان‌آموزان کمک می‌کنند تا به اهداف زبانی خود دست یابند. ما به کیفیت آموزش، رضایت زبان‌آموزان و ایجاد تجربه‌ای مثبت در فرآیند یادگیری اهمیت می‌دهیم.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path' => public_path('assets/web/img/about-img.png'),
        ],
    ],
    'rules' => [
        [
            'title' => 'قوانین و مقررات',
            'body' => 'ما یک آموزشگاه زبان هستیم که با هدف ارتقاء مهارت‌های زبانی افراد در محیطی دوستانه و حرفه‌ای فعالیت می‌کنیم. تیم ما متشکل از اساتید مجرب و متعهد است که با استفاده از روش‌های نوین آموزشی، به زبان‌آموزان کمک می‌کنند تا به اهداف زبانی خود دست یابند. ما به کیفیت آموزش، رضایت زبان‌آموزان و ایجاد تجربه‌ای مثبت در فرآیند یادگیری اهمیت می‌دهیم.',
            'slug' => 'rules',
            'deletable' => App\Enums\YesNoEnum::NO->value,
            'type' => PageTypeEnum::RULES->value,
            'languages' => [
                'fa',
            ],
            'seo_options' => [
                'title' => 'قوانین و مقررات',
                'description' => 'ما یک آموزشگاه زبان هستیم که با هدف ارتقاء مهارت‌های زبانی افراد در محیطی دوستانه و حرفه‌ای فعالیت می‌کنیم. تیم ما متشکل از اساتید مجرب و متعهد است که با استفاده از روش‌های نوین آموزشی، به زبان‌آموزان کمک می‌کنند تا به اهداف زبانی خود دست یابند. ما به کیفیت آموزش، رضایت زبان‌آموزان و ایجاد تجربه‌ای مثبت در فرآیند یادگیری اهمیت می‌دهیم.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path' => public_path('assets/web/img/about-img.png'),
        ],
    ],
];
