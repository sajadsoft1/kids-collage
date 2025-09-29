<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Karno Seeder Batch
 *
 * This batch handles all the Karno-specific data seeding from the
 * original karno.php file, now organized into logical groups.
 * This provides Persian/Farsi content and components for the system.
 */
class KarnoSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('🇮🇷 Seeding Karno Data (Persian Content)...');

        $this->call([
            KarnoContentSeeder::class,  // Categories, blogs, FAQs
            KarnoWebsiteSeeder::class,  // Banners, sliders, comments, opinions
            KarnoTeamSeeder::class,     // Clients, teammates, contact, social media
            KarnoSystemSeeder::class,   // Bulletins, licenses
        ]);

        $this->command->info('✅ Karno Data seeded successfully!');
        $this->command->info('📚 Created Persian content including categories, blogs, and FAQs');
        $this->command->info('🌐 Added website components with Persian text');
        $this->command->info('👥 Seeded team and contact information');
        $this->command->info('⚙️ Added system bulletins and licenses');
    }
}
