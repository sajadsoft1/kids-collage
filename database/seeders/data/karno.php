<?php

declare(strict_types=1);

use App\Enums\BannerSizeEnum;
use App\Enums\CategoryTypeEnum;
use App\Enums\PageTypeEnum;
use App\Enums\SeoRobotsMetaEnum;
use App\Models\Blog;
use App\Models\Category;

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
            'comment' => 'محتوای این بلاگ بسیار مفید بود و به یادگیری زبانم کمک کرد.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'از توضیحات روان و مثال‌های آموزشی لذت بردم. ممنونم.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'مطالب، انگیزه‌ام را برای تمرین زبان انگلیسی بیشتر کرد.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'از نویسنده محترم بابت جمع‌آوری نکات مهم زبان سپاسگزارم.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'خیلی خوشحالم که با وبسایت شما آشنا شدم. آموزش‌ها عالی هستند.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'با توضیحات ساده و مثال‌های خوبتون خیلی راحت‌تر یاد گرفتم.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'یادگیری زبان با مطالب وبلاگ شما واقعا لذت‌بخش شده.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'بسیار سپاسگزارم از راهنمایی‌ها و آموزش‌های خوبتون.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'نکات آموزشی ارائه شده کمک زیادی به پیشرفت من کرد.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'خیلی ممنونم بابت به اشتراک‌گذاری تجربیات در زمینه یادگیری زبان.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'مطالبی که ارائه دادید باعث شد دیدگاه بهتری نسبت به زبان پیدا کنم.',
            'languages' => ['fa'],
        ],
        [
            'published' => true,
            'user_id' => App\Models\User::query()->first()->id,
            'morphable_id' => 1,
            'morphable_type' => Blog::class,
            'comment' => 'آموزش‌های شما باعث تشویق من به ادامه یادگیری شد. سپاسگزارم.',
            'languages' => ['fa'],
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
            'title' => 'شروع ثبت‌نام ترم تابستان کانون زبان',
            'description' => 'فرصت طلایی جهت ثبت‌نام در دوره‌های جدید آموزش زبان.',
            'body' => 'ثبت‌نام ترم تابستانی کانون زبان ایران آغاز شد. علاقه‌مندان می‌توانند از روز شنبه تا پایان هفته آینده با مراجعه به سایت رسمی کانون، فرآیند ثبت‌نام را تکمیل نمایند. این ترم شامل دوره‌هایی برای تمام گروه‌های سنی و سطوح مختلف زبانی می‌باشد.
همچنین کارگاه‌های رایگان مکالمه و تقویت لهجه برای زبان‌آموزان جدید در نظر گرفته شده است. حضور در این دوره‌ها به صورت آنلاین و حضوری امکان‌پذیر است و به شرکت‌کنندگان گواهی معتبر ارائه خواهد شد.',
            'published' => true,
            'category_id' => Category::where('type', CategoryTypeEnum::BULLETIN->value)->first()?->id,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-1.jpg'),
        ],
        [
            'title' => 'برگزاری مسابقات زبان انگلیسی برای نوجوانان',
            'description' => 'اولین دوره مسابقات ملی زبان انگلیسی آغاز شد.',
            'body' => 'کانون زبان ایران اولین دوره مسابقات ملی زبان انگلیسی ویژه گروه سنی نوجوان را برگزار می‌کند. هدف از این مسابقات ارتقاء سطح زبان‌آموزی و ایجاد انگیزه در هنرجویان اعلام شده است. شرکت‌کنندگان می‌توانند در بخش‌های مکالمه، نگارش و شنیداری رقابت کنند.
مهلت ثبت‌نام تا پایان مرداد ماه اعلام گردیده و به نفرات برتر جوایز ارزنده‌ای اهدا خواهد شد. جهت شرکت در مسابقه، ثبت‌نام از طریق سایت کانون امکان‌پذیر است.',
            'published' => true,
            'category_id' => Category::where('type', CategoryTypeEnum::BULLETIN->value)->first()?->id,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-2.jpg'),
        ],
        [
            'title' => 'برنامه آموزشی جدید زبان آلمانی',
            'description' => 'آغاز دوره‌های به‌روزشده زبان آلمانی برای کودکان و بزرگسالان.',
            'body' => 'کانون زبان ایران از تابستان امسال برنامه آموزشی جدیدی برای زبان آلمانی ارائه می‌دهد. این برنامه شامل متدهای نوین آموزشی و منابع صوتی تصویری جدید برای تسهیل یادگیری است. تدریس توسط اساتید مجرب و دارای گواهینامه بین‌المللی صورت خواهد گرفت.
ثبت‌نام در این دوره‌ها تا دهم تیرماه ادامه دارد و علاقه‌مندان می‌توانند در سطوح مبتدی تا پیشرفته شرکت نمایند. در پایان دوره، زبان‌آموزان در آزمون سنجش مهارت شرکت خواهند کرد.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-3.jpg'),
        ],
        [
            'title' => 'اعطای بورسیه آموزشی زبان فرانسوی',
            'description' => 'فرصتی برای دریافت بورسیه شرکت در کلاس‌های زبان فرانسوی.',
            'body' => 'کانون زبان ایران اعلام کرد به ۲۰ نفر از زبان‌آموزان علاقه‌مند که شرایط لازم را داشته باشند بورس آموزشی زبان فرانسوی اعطا می‌شود. این بورس شامل پوشش صد درصدی شهریه دوره و منابع آموزشی است.
هدف از این اقدام، حمایت از توسعه آموزش زبان‌های خارجی میان دانشجویان و دانش‌آموزان مستعد می‌باشد. ثبت‌نام از طریق تکمیل فرم ویژه در سایت رسمی کانون امکان‌پذیر است.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-4.jpg'),
        ],
        [
            'title' => 'آغاز دوره‌های فشرده IELTS برای داوطلبان',
            'description' => 'دوره‌های ویژه آمادگی آزمون IELTS با حضور اساتید بین‌المللی برگزار می‌شود.',
            'body' => 'کانون زبان ایران برای داوطلبان آزمون IELTS، دوره‌های فشرده و تخصصی آماده‌سازی برگزار می‌کند. در این دوره‌ها، علاوه بر تمرین مهارت‌های چهارگانه زبان، دوره‌های بررسی تکنیک‌های تست‌زنی و تحلیل نمونه سوالات نیز وجود دارد.
ظرفیت این دوره‌ها محدود بوده و اولویت با افرادی است که ثبت‌نام زودتر را انجام دهند. ضمناً به پذیرفته‌شدگان این دوره گواهی شرکت صادر خواهد شد.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-5.jpg'),
        ],
        [
            'title' => 'کارگاه رایگان آشنایی با آزمون‌های زبان معتبر',
            'description' => 'برگزاری وبینار آنلاین معرفی آزمون‌های زبان.',
            'body' => 'وبینار رایگانی با محوریت معرفی آزمون‌های معتبر بین‌المللی زبان انگلیسی، شامل TOEFL، IELTS و GRE، توسط کانون زبان برگزار می‌شود. در این وبینار ساختار آزمون‌ها، منابع مناسب و نکات کلیدی موفقیت به زبان‌آموزان ارائه می‌شود.
علاقه‌مندان می‌توانند از طریق سایت کانون نسبت به ثبت‌نام اقدام کنند. حضور در این کارگاه برای عموم آزاد است.',
            'published' => true,
            'category_id' => Category::where('type', CategoryTypeEnum::BULLETIN->value)->first()?->id,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-6.jpg'),
        ],
        [
            'title' => 'نتایج سطح‌بندی زبان‌آموزان اعلام شد',
            'description' => 'نتایج آزمون تعیین سطح ترم بهار منتشر شد.',
            'body' => 'آزمون تعیین سطح ترم بهار برای زبان‌آموزان کانون برگزار گردید و نتایج آن امروز اعلام شد. زبان‌آموزان می‌توانند با ورود به سامانه آموزشی از وضعیت خود مطلع شوند.
بر اساس نتایج، بیش از ۸۵ درصد شرکت‌کنندگان موفق به کسب نمره قبولی شدند و واجد شرایط شرکت در سطوح بالاتر گردیدند.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-7.jpg'),
        ],
        [
            'title' => 'افزوده شدن زبان چینی به دوره‌های کانون',
            'description' => 'آغاز ثبت‌نام برای کلاس‌های زبان چینی',
            'body' => 'در راستای توسعه آموزش زبان‌های آسیایی، کانون زبان ایران اقدام به برگزاری کلاس‌های زبان چینی برای اولین بار کرده است. این دوره‌ها با استفاده از منابع آموزشی استاندارد و مدرسان باتجربه برگزار می‌شود.
ثبت‌نام‌کنندگان در کلاس‌های زبان چینی می‌توانند از تخفیف ویژه افتتاحیه بهره‌مند شوند. ظرفیت این کلاس‌ها محدود است.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-8.jpg'),
        ],
        [
            'title' => 'انتشار کتاب جدید آموزش لغات پرتغالی',
            'description' => 'کتاب لغت جدید ویژه زبان‌آموزان پرتغالی ارائه شد.',
            'body' => 'کانون زبان ایران کتاب جدیدی به منظور آموزش واژگان کاربردی زبان پرتغالی منتشر کرد. این کتاب با هدف تسهیل فرآیند یادگیری لغات پرتغالی و تمرکز بر کاربرد واقعی آن‌ها طراحی شده است.
زبان‌آموزان می‌توانند نسخه الکترونیکی کتاب را از سایت کانون دانلود کنند یا برای تهیه نسخه چاپی به واحدهای آموزشی مراجعه نمایند.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-9.jpg'),
        ],
        [
            'title' => 'معرفی سامانه هوشمند تعیین سطح آنلاین',
            'description' => 'راه‌اندازی سیستم تعیین سطح آنلاین برای زبان‌آموزان.',
            'body' => 'سامانه هوشمند تعیین سطح آنلاین با هدف تسهیل فرآیند ورود و ارتقاء سطح زبان‌آموزان راه‌اندازی شد. با استفاده از این سامانه، زبان‌آموزان می‌توانند بدون نیاز به حضور فیزیکی و در کمتر از یک ساعت، سطح زبان خود را ارزیابی کنند.
این سامانه در دسترس کلیه داوطلبان قرار دارد و گزارش دقیق سطح زبانی را ارائه می‌دهد.',
            'published' => true,
            'category_id' => 4,
            'languages' => ['fa'],
            'path' => public_path('assets/web/img/news-10.jpg'),
        ],
    ],
    [
        'title' => 'اطلاعیه مهم',
        'description' => 'توضیحات اطلاعیه مهم',
        'body' => 'متن کامل اطلاعیه مهم',
        'published' => true,
        'category_id' => 4,
        'languages' => ['fa'],
        'path' => public_path('assets/web/img/news-2-img-2.jpg'),
    ],
    [
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
    ],
    [
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
            'path' => public_path('assets/web/img/about-img.png'),
        ],
    ],
    'categories' => [
        [
            'title' => 'لاراول',
            'slug' => 'لاراول',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'body' => 'این فریم‌ورک با هدف ساده‌سازی توسعه برنامه‌های تحت وب ایجاد شده و امکاناتی مثل مسیریابی ساده، مدیریت پایگاه‌داده با Eloquent ORM، سیستم صف، احراز هویت، ارسال ایمیل، و قالب Blade رو در اختیار توسعه‌دهنده قرار می‌ده.لاراول با سینتکس زیبا و ابزارهای حرفه‌ای، توسعه پروژه‌های کوچک تا بزرگ رو سریع‌تر و لذت‌بخش‌تر می‌کنه.',
            'seo_options' => [
                'title' => 'لاراول',
                'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type' => CategoryTypeEnum::BLOG->value,
            'ordering' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/categories/laravel-cat.png'),
        ],
        [
            'title' => 'PORTFOLIO',
            'slug' => 'PORTFOLIO',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'body' => 'این فریم‌ورک با هدف ساده‌سازی توسعه برنامه‌های تحت وب ایجاد شده و امکاناتی مثل مسیریابی ساده، مدیریت پایگاه‌داده با Eloquent ORM، سیستم صف، احراز هویت، ارسال ایمیل، و قالب Blade رو در اختیار توسعه‌دهنده قرار می‌ده.لاراول با سینتکس زیبا و ابزارهای حرفه‌ای، توسعه پروژه‌های کوچک تا بزرگ رو سریع‌تر و لذت‌بخش‌تر می‌کنه.',
            'seo_options' => [
                'title' => 'PORTFOLIO',
                'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type' => CategoryTypeEnum::PORTFOLIO->value,
            'ordering' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/categories/laravel-cat.png'),
        ],
        [
            'title' => 'FAQ',
            'slug' => 'FAQ',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'body' => 'این فریم‌ورک با هدف ساده‌سازی توسعه برنامه‌های تحت وب ایجاد شده و امکاناتی مثل مسیریابی ساده، مدیریت پایگاه‌داده با Eloquent ORM، سیستم صف، احراز هویت، ارسال ایمیل، و قالب Blade رو در اختیار توسعه‌دهنده قرار می‌ده.لاراول با سینتکس زیبا و ابزارهای حرفه‌ای، توسعه پروژه‌های کوچک تا بزرگ رو سریع‌تر و لذت‌بخش‌تر می‌کنه.',
            'seo_options' => [
                'title' => 'FAQ',
                'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type' => CategoryTypeEnum::FAQ->value,
            'ordering' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/categories/laravel-cat.png'),
        ],
        [
            'title' => 'BULLETIN',
            'slug' => 'BULLETIN',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'body' => 'این فریم‌ورک با هدف ساده‌سازی توسعه برنامه‌های تحت وب ایجاد شده و امکاناتی مثل مسیریابی ساده، مدیریت پایگاه‌داده با Eloquent ORM، سیستم صف، احراز هویت، ارسال ایمیل، و قالب Blade رو در اختیار توسعه‌دهنده قرار می‌ده.لاراول با سینتکس زیبا و ابزارهای حرفه‌ای، توسعه پروژه‌های کوچک تا بزرگ رو سریع‌تر و لذت‌بخش‌تر می‌کنه.',
            'seo_options' => [
                'title' => 'BULLETIN',
                'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type' => CategoryTypeEnum::BULLETIN->value,
            'ordering' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/categories/laravel-cat.png'),
        ],
        [
            'title' => 'COURSE',
            'slug' => 'COURSE',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'body' => 'این فریم‌ورک با هدف ساده‌سازی توسعه برنامه‌های تحت وب ایجاد شده و امکاناتی مثل مسیریابی ساده، مدیریت پایگاه‌داده با Eloquent ORM، سیستم صف، احراز هویت، ارسال ایمیل، و قالب Blade رو در اختیار توسعه‌دهنده قرار می‌ده.لاراول با سینتکس زیبا و ابزارهای حرفه‌ای، توسعه پروژه‌های کوچک تا بزرگ رو سریع‌تر و لذت‌بخش‌تر می‌کنه.',
            'seo_options' => [
                'title' => 'COURSE',
                'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type' => CategoryTypeEnum::COURSE->value,
            'ordering' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/categories/laravel-cat.png'),
        ],
        [
            'title' => 'QUESTION',
            'slug' => 'QUESTION',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'body' => 'این فریم‌ورک با هدف ساده‌سازی توسعه برنامه‌های تحت وب ایجاد شده و امکاناتی مثل مسیریابی ساده، مدیریت پایگاه‌داده با Eloquent ORM، سیستم صف، احراز هویت، ارسال ایمیل، و قالب Blade رو در اختیار توسعه‌دهنده قرار می‌ده.لاراول با سینتکس زیبا و ابزارهای حرفه‌ای، توسعه پروژه‌های کوچک تا بزرگ رو سریع‌تر و لذت‌بخش‌تر می‌کنه.',
            'seo_options' => [
                'title' => 'QUESTION',
                'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type' => CategoryTypeEnum::QUESTION->value,
            'ordering' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/categories/laravel-cat.png'),
        ],
        [
            'title' => 'EVENT',
            'slug' => 'EVENT',
            'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
            'body' => 'این فریم‌ورک با هدف ساده‌سازی توسعه برنامه‌های تحت وب ایجاد شده و امکاناتی مثل مسیریابی ساده، مدیریت پایگاه‌داده با Eloquent ORM، سیستم صف، احراز هویت، ارسال ایمیل، و قالب Blade رو در اختیار توسعه‌دهنده قرار می‌ده.لاراول با سینتکس زیبا و ابزارهای حرفه‌ای، توسعه پروژه‌های کوچک تا بزرگ رو سریع‌تر و لذت‌بخش‌تر می‌کنه.',
            'seo_options' => [
                'title' => 'EVENT',
                'description' => 'لاراول (Laravel) یک فریم‌ورک محبوب و قدرتمند برای توسعه وب با زبان PHP است که بر پایه معماری MVC (Model-View-Controller) ساخته شده.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type' => CategoryTypeEnum::EVENT->value,
            'ordering' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/categories/laravel-cat.png'),
        ],
    ],
    'blogs' => [
        [
            'title' => 'شروع سریع با لاراول: راهنمای مبتدیان',
            'description' => 'مقدمه‌ای عملی بر لاراول برای کسانی که می‌خواهند سریع پروژه‌های خود را با این فریم‌ورک شروع کنند.',
            'body' => 'لاراول یکی از فریم‌ورک‌های قدرتمند و در عین حال ساده PHP است که برای توسعه سریع وب‌اپلیکیشن‌ها طراحی شده.در این مقاله قدم‌به‌قدم نحوه نصب لاراول، اجرای اولین پروژه و ساخت صفحات ساده را یاد می‌گیریم.مفاهیم پایه مانند روتینگ، کنترلرها، ویوها و مدل‌ها معرفی می‌شن.هدف اینه که بدون نیاز به دانش عمیق قبلی، خیلی سریع وارد دنیای لاراول بشی.از نصب Composer تا اجرای اولین صفحه با Blade، همه چیز رو پوشش می‌دیم.اگه تازه‌کاری، این مقاله یه نقطه شروع عالی برات خواهد بود.',
            'slug' => 'شروع-سریع-با-لاراول:-راهنمای-مبتدیان',
            'published' => true,
            'published_at' => now(),
            'user_id' => 2,
            'category_id' => 1,
            'view_count' => 2,
            'comment_count' => 1,
            'wish_count' => 2,
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/blogs/laravel.jpg'),
        ],
        [
            'title' => 'معماری لاراول: نگاهی عمیق به ساختار داخلی فریم‌ورک',
            'description' => 'ساختار MVC، سرویس کانتینرها، فَسادها و سایر مفاهیم کلیدی را بررسی می‌کنیم تا درک عمیق‌تری از لاراول پیدا کنید.',
            'body' => '',
            'slug' => 'معماری-لاراول:-نگاهی-عمیق-به-ساختار-داخلی-فریم‌ورک',
            'published' => true,
            'published_at' => now(),
            'user_id' => 3,
            'category_id' => 1,
            'view_count' => 2,
            'comment_count' => 1,
            'wish_count' => 2,
            'languages' => [
                'fa',
            ],
            'path' => public_path('images/test/blogs/design.jpg'),
        ],
    ],
    'faq' => [
        [
            'title' => 'چگونه می‌توانم در دوره‌های آموزش زبان ثبت‌نام کنم؟',
            'description' => 'برای ثبت‌نام کافیست به صفحه ثبت‌نام سایت مراجعه کنید و فرم مربوطه را پر کنید یا با پشتیبانی تماس بگیرید.',
            'published' => true,
            'favorite' => true,
            'ordering' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'چه مدارکی برای ثبت‌نام در دوره زبان نیاز است؟',
            'description' => 'برای ثبت‌نام فقط ارائه کارت شناسایی معتبر کافی است و نیازی به مدرک خاصی نیست.',
            'published' => true,
            'favorite' => false,
            'ordering' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'هزینه کلاس زبان چقدر است و چگونه پرداخت کنم؟',
            'description' => 'هزینه کلاس‌ها بسته به سطح و نوع دوره متفاوت است. پرداخت به صورت آنلاین یا حضوری امکان‌پذیر است.',
            'published' => true,
            'favorite' => false,
            'ordering' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'آیا می‌توانم اقساطی شهریه را پرداخت کنم؟',
            'description' => 'بله، امکان پرداخت شهریه به صورت اقساطی برای برخی دوره‌ها وجود دارد. جهت اطلاعات بیشتر با بخش مالی تماس بگیرید.',
            'published' => true,
            'favorite' => false,
            'ordering' => 4,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'شرایط ثبت‌نام برای افراد زیر ۱۸ سال چیست؟',
            'description' => 'برای افراد زیر ۱۸ سال حضور والدین یا ارائه رضایت‌نامه الزامی است.',
            'published' => true,
            'favorite' => false,
            'ordering' => 5,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'آیا بعد از ثبت‌نام حتما باید تعیین سطح بدهم؟',
            'description' => 'بله، برای تعیین سطح مناسب کلاس، بعد از ثبت‌نام این آزمون از شما گرفته می‌شود.',
            'published' => true,
            'favorite' => false,
            'ordering' => 6,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'در صورت انصراف از دوره هزینه بازگشت داده می‌شود؟',
            'description' => 'شرایط بازگشت وجه بر اساس قوانین موسسه صورت می‌گیرد. لطفا بخش قوانین ثبت‌نام را مطالعه فرمایید.',
            'published' => true,
            'favorite' => false,
            'ordering' => 7,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'ثبت‌نام برای کدام زبان‌ها امکان‌پذیر است؟',
            'description' => 'در حال حاضر ثبت‌نام برای زبان‌های انگلیسی، آلمانی، فرانسه، اسپانیایی و ترکی انجام می‌پذیرد.',
            'published' => true,
            'favorite' => false,
            'ordering' => 8,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'آیا می‌توانم به صورت آنلاین ثبت‌نام کنم؟',
            'description' => 'بله، تمام مراحل ثبت‌نام از طریق سایت و به صورت آنلاین قابل انجام است.',
            'published' => true,
            'favorite' => false,
            'ordering' => 9,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
        [
            'title' => 'پس از ثبت‌نام چگونه اطلاعات کلاس خود را دریافت کنم؟',
            'description' => 'پس از تکمیل ثبت‌نام جزئیات کلاس و زمان شروع از طریق پیامک یا تماس اطلاع‌رسانی می‌شود.',
            'published' => true,
            'favorite' => false,
            'ordering' => 10,
            'created_at' => now(),
            'updated_at' => now(),
            'languages' => ['fa'],
        ],
    ],
    'events' => [
        [
            'title' => 'شروع سریع با لاراول: راهنمای مبتدیان',
            'description' => 'مقدمه‌ای عملی بر لاراول برای کسانی که می‌خواهند سریع پروژه‌های خود را با این فریم‌ورک شروع کنند.',
            'body' => 'لاراول یکی از فریم‌ورک‌های قدرتمند و در عین حال ساده PHP است که برای توسعه سریع وب‌اپلیکیشن‌ها طراحی شده.در این مقاله قدم‌به‌قدم نحوه نصب لاراول، اجرای اولین پروژه و ساخت صفحات ساده را یاد می‌گیریم.مفاهیم پایه مانند روتینگ، کنترلرها، ویوها و مدل‌ها معرفی می‌شن.هدف اینه که بدون نیاز به دانش عمیق قبلی، خیلی سریع وارد دنیای لاراول بشی.از نصب Composer تا اجرای اولین صفحه با Blade، همه چیز رو پوشش می‌دیم.اگه تازه‌کاری، این مقاله یه نقطه شروع عالی برات خواهد بود.',
            'slug' => 'شروع-سریع-با-لاراول:-راهنمای-مبتدیان',
            'published' => true,
            'published_at' => now(),
            'category_id' => 1,
            'view_count' => 2,
            'comment_count' => 1,
            'wish_count' => 2,
            'languages' => [
                'fa',
            ],
            'seo_options' => [
                'title' => 'شروع سریع با لاراول: راهنمای مبتدیان',
                'description' => 'مقدمه‌ای عملی بر لاراول برای کسانی که می‌خواهند سریع پروژه‌های خود را با این فریم‌ورک شروع کنند.',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path' => public_path('assets/images/test/blogs/laravel.jpg'),
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(12),
            'location' => 'تهران، ایران',
            'capacity' => 100,
            'price' => 500000,
            'is_online' => false,
        ],
    ],
    'opinion' => [
        [
            'published' => true,
            'ordering' => 1,
            'user_name' => 'آرش محمدی',
            'comment' => 'بسیار از دوره‌های کارنو راضی هستم. دخترم نه تنها انگلیسی را با علاقه یاد می‌گیرد، بلکه اعتماد به نفسش هم خیلی بیشتر شده.',
            'path' => public_path('assets/web/img/parent-1.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 2,
            'user_name' => 'ترانه احمدی',
            'comment' => 'کلاس‌ها خیلی جذاب هستند. معلم‌ها با حوصله و مهربان‌اند و یادگیری شیرین‌تر از همیشه‌است.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/student-1.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 3,
            'user_name' => 'سارا احمدیان',
            'comment' => 'کارنو محیطی پویا و دوستانه برای یادگیری زبان فراهم کرده است. همکاری با این مجموعه تجربه بسیار خوبی بود.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/teacher-1.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 4,
            'user_name' => 'پویان نیازی',
            'comment' => 'بعد از شرکت در کلاس‌های آنلاین سطح زبان انگلیسیم واقعا پیشرفت کرد و حالا راحت‌تر فیلم و موزیک گوش می‌دهم.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/student-2.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 5,
            'user_name' => 'مهتاب رضایی',
            'comment' => 'برنامه‌ریزی کلاس‌ها عالی است و برخورد کادر آموزشی بسیار صمیمی و حرفه‌ای بود.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/parent-2.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 6,
            'user_name' => 'علی غفاری',
            'comment' => 'محتوای آموزشی به‌روز و ابزارهای کمک آموزشی متنوعی ارائه می‌شود. باعث یادگیری بهتر دانش‌آموزان است.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/teacher-2.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 7,
            'user_name' => 'نیکی مرادی',
            'comment' => 'دوستای جدید پیدا کردم و با هم انگلیسی تمرین می‌کنیم. واقعا لذت‌بخش بود.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/student-3.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 8,
            'user_name' => 'حامد نجفی',
            'comment' => 'پیشرفت پسرم در مکالمه فوق‌العاده محسوس است. خیلی ممنون از مربیان کارنو.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/parent-3.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 9,
            'user_name' => 'رادین بیات',
            'comment' => 'فضای دوستانه و امکانات کلاس‌ها عالی بود. هر هفته منتظر کلاس جدیدم بودم!',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/student-4.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 10,
            'user_name' => 'زهرا اکبری',
            'comment' => 'آموزش شخصی‌سازی‌شده و توجه به نیازهای هر دانش‌آموز، کار در کارنو را لذت‌بخش می‌کند.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/teacher-3.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 11,
            'user_name' => 'فرهاد یوسفی',
            'comment' => 'فرزندم پیشرفت چشمگیری در زبان داشته و علاقه‌اش به یادگیری زبان انگلیسی دوچندان شده است.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/parent-4.jpg'),
        ],
        [
            'published' => true,
            'ordering' => 12,
            'user_name' => 'یاسمن جواهری',
            'comment' => 'مطالب درس خیلی خوب و واضح توضیح داده می‌شوند و تمرین‌ها هم به یادگیری بیشتر کمک می‌کنند.',
            'languages' => [
                'fa',
            ],
            'path' => public_path('assets/web/img/student-5.jpg'),
        ],
    ],
];
