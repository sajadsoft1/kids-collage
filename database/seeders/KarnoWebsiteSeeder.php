<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Banner\StoreBannerAction;
use App\Actions\Comment\StoreCommentAction;
use App\Actions\Opinion\StoreOpinionAction;
use App\Actions\Slider\StoreSliderAction;
use Exception;
use Illuminate\Database\Seeder;

/**
 * Karno Website Seeder
 *
 * Seeds website components from karno data files including
 * banners, sliders, comments, and opinions.
 */
class KarnoWebsiteSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('🌐 Seeding Karno Website Components...');

        $data = require database_path('seeders/data/karno_website.php');

        // Seed banners
        $this->seedBanners($data['banner']);

        // Seed sliders
        $this->seedSliders($data['slider']);

        // Seed comments
        $this->seedComments($data['comment']);

        // Seed opinions
        $this->seedOpinions($data['opinion']);

        $this->command->info('✅ Karno Website Components seeded successfully!');
    }

    /** Seed banners from karno data */
    private function seedBanners(array $banners): void
    {
        $this->command->info('🖼️ Seeding banners...');

        foreach ($banners as $row) {
            $banner = StoreBannerAction::run([
                'title'       => $row['title'],
                'description' => $row['description'],
                'published'   => $row['published'],
                'size'        => $row['size'],
                'languages'   => $row['languages'],
            ]);

            // Add images to the banners
            try {
                $banner->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }

        $this->command->info('✅ Banners seeded successfully!');
    }

    /** Seed sliders from karno data */
    private function seedSliders(array $sliders): void
    {
        $this->command->info('🎠 Seeding sliders...');

        foreach ($sliders as $row) {
            $slider = StoreSliderAction::run([
                'title'       => $row['title'],
                'description' => $row['description'],
                'published'   => $row['published'],
                'ordering'    => $row['ordering'],
                'link'        => $row['link'],
                'position'    => $row['position'],
                'languages'   => $row['languages'],
            ]);

            // Add images to the sliders
            try {
                $slider->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }

        $this->command->info('✅ Sliders seeded successfully!');
    }

    /** Seed comments from karno data */
    private function seedComments(array $comments): void
    {
        $this->command->info('💬 Seeding comments...');

        foreach ($comments as $row) {
            try {
                StoreCommentAction::run([
                    'published'      => $row['published'],
                    'user_id'        => $row['user_id'],
                    'morphable_id'   => $row['morphable_id'],
                    'morphable_type' => $row['morphable_type'],
                    'comment'        => $row['comment'],
                ]);
            } catch (Exception $e) {
                // If comment creation fails, skip it
                $this->command->warn('Comment creation failed, skipping...');

                continue;
            }
        }

        $this->command->info('✅ Comments seeded successfully!');
    }

    /** Seed opinions from karno data */
    private function seedOpinions(array $opinions): void
    {
        $this->command->info('⭐ Seeding opinions...');

        foreach ($opinions as $row) {
            try {
                $opinion = StoreOpinionAction::run([
                    'published' => $row['published'],
                    'ordering'  => $row['ordering'],
                    'company'   => $row['company'],
                    'user_name' => $row['user_name'],
                    'comment'   => $row['comment'],
                    'image'     => $row['path'],
                ]);

                // Set languages manually since StoreOpinionAction doesn't handle it
                $opinion->update(['languages' => $row['languages']]);
            } catch (Exception $e) {
                // If opinion creation fails, skip it
                $this->command->warn('Opinion creation failed, skipping...');

                continue;
            }
        }

        $this->command->info('✅ Opinions seeded successfully!');
    }
}
