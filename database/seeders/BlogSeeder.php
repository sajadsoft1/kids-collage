<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Blog\StoreBlogAction;
use App\Enums\UserTypeEnum;
use App\Models\Blog;
use App\Models\User;
use Exception;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Random\RandomException;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        $user= User::factory()->create([
            'type'=>UserTypeEnum::EMPLOYEE->value
        ]);
        $user->addMedia(public_path('assets/web/img/teacher-1.jpg'))
            ->preservingOriginal()
            ->toMediaCollection('avatar');
        foreach ($data['blogs'] as $row) {
            $blog = StoreBlogAction::run([
                'slug'            => $row['slug'],
                'title'           => $row['title'],
                'description'     => $row['description'],
                'body'            => $row['body'],
                'category_id'     => $row['category_id'],
                'user_id'         => $user->id??$row['user_id'],
                'view_count'      => $row['view_count'],
                'comment_count'   => $row['comment_count'],
                'wish_count'      => $row['wish_count'],
                'published'       => $row['published'],
                'published_at'    => $row['published_at'],
                'seo_title'       => $row['seo_options']['title'],
                'seo_description' => $row['seo_options']['description'],
                'canonical'       => $row['seo_options']['canonical'],
                'old_url'         => $row['seo_options']['old_url'],
                'redirect_to'     => $row['seo_options']['redirect_to'],
                'robots_meta'     => $row['seo_options']['robots_meta'],
            ]);

            // Add image for the blogs

            try {
                $blog->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }

        $faker = Faker::create();

        Blog::all()->each(function ($blog) use ($faker) {
            $blogCommentsRelation = $blog->comments();

            // لیست نظرات واقعی برای مقالات
            $comments = [
                'مقاله بسیار مفیدی بود، از شما ممنونم!',
                'دیدگاه شما در این مورد جالب بود، کاملاً با شما موافقم.',
                'میشه بیشتر در مورد این موضوع توضیح بدین؟ دوست دارم بیشتر بدونم.',
                'بسیار عالی! منتظر مقالات بعدی شما هستم.',
                'با نظر شما موافق نیستم، اما استدلال شما جالب بود.',
                'دقیقاً دنبال همچین مطلبی بودم، خیلی کمک کرد!',
                'مطلب رو با دوستانم به اشتراک گذاشتم، خیلی کاربردی بود.',
                'خیلی خوب توضیح دادید، اما فکر می‌کنم بعضی بخش‌ها نیاز به جزئیات بیشتری دارند.',
                'بینش فوق‌العاده‌ای داشت! آیا کتابی در این زمینه پیشنهاد می‌کنید؟',
                'نوشته فوق‌العاده‌ای بود! کمک زیادی کرد تا این موضوع رو بهتر درک کنم.',
            ];

            foreach (range(1, 5) as $item) {
                $blogCommentsRelation->create([
                    'user_id'   => random_int(2, 12), // فرض بر این است که کاربران در دیتابیس موجود هستند
                    'comment'   => $faker->randomElement($comments),
                    'published' => true,
                ]);
            }
        });
    }
}
