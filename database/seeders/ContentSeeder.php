<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Content Seeder Batch
 *
 * This batch handles all the content-related data including blog posts,
 * categories, comments, and other content that users will interact with.
 * This is the main content layer of the website.
 */
class ContentSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('📝 Seeding Content...');

        $this->call([
            CategorySeeder::class,  // Content categories
            BlogSeeder::class,      // Blog posts and articles
            CommentSeeder::class,   // User comments
            FaqSeeder::class,       // Frequently asked questions
            OpinionSeeder::class,   // User opinions and testimonials
            BulletinSeeder::class,  // Announcements and bulletins (depends on categories)
            KarnoSeeder::class,     // Persian content and components from karno data
        ]);

        $this->command->info('✅ Content seeded successfully!');
    }
}
