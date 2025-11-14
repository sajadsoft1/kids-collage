<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Blog\StoreBlogAction;
use App\Actions\Category\StoreCategoryAction;
use App\Actions\Faq\StoreFaqAction;
use Exception;
use Illuminate\Database\Seeder;

/**
 * Karno Content Seeder
 *
 * Seeds content-related data from karno data files including
 * categories, blogs, and FAQs with proper SEO options and media.
 */
class KarnoContentSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('📚 Seeding Karno Content Data...');

        $data = require database_path('seeders/data/karno_content.php');

        // Seed categories first
        $this->seedCategories($data['categories']);

        // Seed blogs
        $this->seedBlogs($data['blogs']);

        // Seed FAQs
        $this->seedFaqs($data['faq']);

        $this->command->info('✅ Karno Content Data seeded successfully!');
    }

    /** Seed categories from karno data */
    private function seedCategories(array $categories): void
    {
        $this->command->info('📁 Seeding categories...');

        foreach ($categories as $row) {
            // Use StoreCategoryAction to handle translations properly
            try {
                $category = StoreCategoryAction::run([
                    'slug' => $row['slug'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'body' => $row['body'],
                    'type' => $row['type'],
                    'parent_id' => $row['parent_id'],
                    'published' => true,
                    'ordering' => $row['ordering'],
                    'seo_title' => $row['seo_options']['title'],
                    'seo_description' => $row['seo_options']['description'],
                    'canonical' => $row['seo_options']['canonical'],
                    'old_url' => $row['seo_options']['old_url'],
                    'redirect_to' => $row['seo_options']['redirect_to'],
                    'robots_meta' => $row['seo_options']['robots_meta'],
                    'created_at' => $row['created_at'],
                    'image' => $row['path'],
                ]);
            } catch (Exception $e) {
                // If category already exists, skip it
                $this->command->warn("Category with slug '{$row['slug']}' already exists, skipping...");

                continue;
            }
        }

        $this->command->info('✅ Categories seeded successfully!');
    }

    /** Seed blogs from karno data */
    private function seedBlogs(array $blogs): void
    {
        $this->command->info('📝 Seeding blogs...');

        foreach ($blogs as $row) {
            try {
                $blog = StoreBlogAction::run([
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'body' => $row['body'],
                    'slug' => $row['slug'],
                    'published' => $row['published'],
                    'published_at' => $row['published_at'],
                    'user_id' => $row['user_id'],
                    'category_id' => $row['category_id'],
                    'view_count' => $row['view_count'],
                    'comment_count' => $row['comment_count'],
                    'wish_count' => $row['wish_count'],
                    'languages' => $row['languages'],
                    'seo_title' => $row['seo_options']['title'],
                    'seo_description' => $row['seo_options']['description'],
                    'canonical' => $row['seo_options']['canonical'],
                    'old_url' => $row['seo_options']['old_url'],
                    'redirect_to' => $row['seo_options']['redirect_to'],
                    'robots_meta' => $row['seo_options']['robots_meta'],
                    'image' => $row['path'],
                ]);
            } catch (Exception $e) {
                // If blog already exists, skip it
                $this->command->warn("Blog with slug '{$row['slug']}' already exists, skipping...");

                continue;
            }
        }

        $this->command->info('✅ Blogs seeded successfully!');
    }

    /** Seed FAQs from karno data */
    private function seedFaqs(array $faqs): void
    {
        $this->command->info('❓ Seeding FAQs...');

        foreach ($faqs as $row) {
            StoreFaqAction::run([
                'title' => $row['title'],
                'description' => $row['description'],
                'published' => $row['published'],
                'favorite' => $row['favorite'],
                'ordering' => $row['ordering'],
                'languages' => $row['languages'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ]);
        }

        $this->command->info('✅ FAQs seeded successfully!');
    }
}
