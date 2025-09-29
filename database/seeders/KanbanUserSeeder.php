<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Kanban User Seeder
 *
 * Creates and manages users specifically for the Kanban system.
 * This seeder ensures there are test users available for board
 * ownership, card assignments, and activity tracking.
 */
class KanbanUserSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('👤 Creating Kanban users...');

        // Create or get the main developer user for Kanban
        $developer = User::firstOrCreate(
            ['email' => 'developer@gmail.com'],
            [
                'name'     => 'Admin',
                'family'   => 'User',
                'password' => bcrypt('password'),
                'status'   => 1,
            ]
        );

        // Create or get the admin user for Kanban (using existing admin)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'System',
                'family'   => 'Administrator',
                'password' => bcrypt('password'),
                'status'   => 1,
            ]
        );

        $this->command->info("✅ Created/found developer user: {$developer->email}");
        $this->command->info("✅ Created/found admin user: {$admin->email}");

        // Store users in a way other seeders can access them
        $this->command->getOutput()->write('Kanban users ready for board and card creation.');
    }
}
