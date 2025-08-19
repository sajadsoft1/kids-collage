<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NecessarySeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
