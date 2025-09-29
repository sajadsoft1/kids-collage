<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Website Requirements Seeder Batch
 *
 * This batch handles all the essential website elements including users,
 * UI components, contact forms, and other website-specific functionality.
 * These are the components that make the website functional and user-friendly.
 */
class WebsiteRequirementsSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('🌐 Seeding Website Requirements...');

        $this->call([
            UserSeeder::class,        // Regular users and test accounts
            BannerSeeder::class,      // Website banners and promotions
            SliderSeeder::class,      // Homepage sliders
            ClientSeeder::class,      // Client testimonials and logos
            TeammateSeeder::class,    // Team member profiles
            ContactUsSeeder::class,   // Contact form submissions
            SocialMediaSeeder::class, // Social media links and settings
            TicketSeeder::class,      // Support ticket system
            LicenseSeeder::class,     // License information
        ]);

        $this->command->info('✅ Website Requirements seeded successfully!');
    }
}
