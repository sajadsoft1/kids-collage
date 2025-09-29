<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Bulletin\StoreBulletinAction;
use App\Actions\License\StoreLicenseAction;
use Exception;
use Illuminate\Database\Seeder;

/**
 * Karno System Seeder
 *
 * Seeds system-related data from karno data files including
 * bulletins and licenses.
 */
class KarnoSystemSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('⚙️ Seeding Karno System Data...');

        $data = require database_path('seeders/data/karno_system.php');

        // Seed bulletins
        $this->seedBulletins($data['bulletin']);

        // Seed licenses
        $this->seedLicenses($data['license']);

        $this->command->info('✅ Karno System Data seeded successfully!');
    }

    /** Seed bulletins from karno data */
    private function seedBulletins(array $bulletins): void
    {
        $this->command->info('📢 Seeding bulletins...');

        foreach ($bulletins as $row) {
            try {
                $bulletin = StoreBulletinAction::run([
                    'title'       => $row['title'],
                    'description' => $row['description'],
                    'published'   => $row['published'],
                    'slug'        => \Illuminate\Support\Str::slug($row['title']), // Generate slug from title
                    'image'       => $row['path'],
                ]);
            } catch (Exception $e) {
                // If bulletin creation fails, skip it
                $this->command->warn('Bulletin creation failed, skipping...');

                continue;
            }
        }

        $this->command->info('✅ Bulletins seeded successfully!');
    }

    /** Seed licenses from karno data */
    private function seedLicenses(array $licenses): void
    {
        $this->command->info('📄 Seeding licenses...');

        foreach ($licenses as $row) {
            try {
                $license = StoreLicenseAction::run([
                    'title'       => $row['title'],
                    'description' => $row['description'],
                    'slug'        => \Illuminate\Support\Str::slug($row['title']), // Generate slug from title
                    'image'       => $row['path'],
                ]);
            } catch (Exception $e) {
                // If license creation fails, skip it
                $this->command->warn('License creation failed, skipping...');

                continue;
            }
        }

        $this->command->info('✅ Licenses seeded successfully!');
    }
}
