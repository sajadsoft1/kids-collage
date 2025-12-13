<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Kanban Board Seeder
 *
 * Creates sample Kanban boards with different purposes and configurations.
 * This seeder sets up the main structure for project management and
 * other organizational workflows.
 */
class KanbanBoardSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('📋 Creating Kanban boards...');

        // Get users for board ownership
        $developer = User::where('email', 'developer@gmail.com')->first();
        $admin = User::where('email', 'employee@gmail.com')->first();

        if ( ! $developer || ! $admin) {
            $this->command->error('Required users not found. Please run KanbanUserSeeder first.');

            return;
        }

        // Create main project management board
        $projectBoard = Board::firstOrCreate(
            ['name' => 'Project Management'],
            [
                'description' => 'Main project management board for tracking tasks and features',
                'color' => '#3B82F6',
                'is_active' => true,
            ]
        );

        // Add developer as owner
        if ( ! $projectBoard->users()->where('user_id', $developer->id)->exists()) {
            $projectBoard->users()->attach($developer->id, ['role' => 'owner']);
        }

        // Create marketing campaign board
        $marketingBoard = Board::firstOrCreate(
            ['name' => 'Marketing Campaign'],
            [
                'description' => 'Board for tracking marketing activities and campaigns',
                'color' => '#EC4899',
                'is_active' => true,
            ]
        );

        // Add admin as admin
        if ( ! $marketingBoard->users()->where('user_id', $admin->id)->exists()) {
            $marketingBoard->users()->attach($admin->id, ['role' => 'admin']);
        }

        $this->command->info("✅ Created/found Project Management board (ID: {$projectBoard->id})");
        $this->command->info("✅ Created/found Marketing Campaign board (ID: {$marketingBoard->id})");
    }
}
