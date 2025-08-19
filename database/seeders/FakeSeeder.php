<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakeSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BlogSeeder::class,
            TicketSeeder::class,
            FaqSeeder::class,
            BannerSeeder::class,
            CommentSeeder::class,
            ClientSeeder::class,
            TeammateSeeder::class,
            ContactUsSeeder::class,
            OpinionSeeder::class,
            SocialMediaSeeder::class,
        ]);
    }
}
