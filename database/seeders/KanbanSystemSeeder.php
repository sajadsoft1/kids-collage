<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Kanban System Seeder Batch
 *
 * This batch handles the Kanban project management system including
 * boards, columns, cards, and all related functionality. This is
 * a standalone project management feature.
 */
class KanbanSystemSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('📋 Seeding Kanban System...');

        $this->call([
            KanbanBoardSeeder::class,  // Create boards with metadata
            KanbanColumnSeeder::class, // Create columns for boards
            KanbanCardSeeder::class,   // Create cards with tasks and history
        ]);

        $this->command->info('✅ Kanban System seeded successfully!');
        $this->command->info('📊 Created modular Kanban structure with users, boards, columns, and cards');
        $this->command->info('🔑 Login with: admin@example.com / password or developer@gmail.com / password');
    }
}
