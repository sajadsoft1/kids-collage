<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * System Defaults Seeder Batch
 *
 * This batch handles all the core system requirements that must be present
 * for the application to function properly. These are the foundational
 * elements that other parts of the system depend on.
 */
class SystemDefaultsSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('🌱 Seeding System Defaults...');

        $this->call([
            RolePermissionSeeder::class,  // Core roles and permissions
            AdminSeeder::class,           // Default admin user
            SettingSeeder::class,         // System settings
            PageSeeder::class
        ]);

        $this->command->info('✅ System Defaults seeded successfully!');
    }
}
