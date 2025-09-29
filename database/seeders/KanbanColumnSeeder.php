<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Board;
use Illuminate\Database\Seeder;

/**
 * Kanban Column Seeder
 *
 * Creates columns for existing Kanban boards. This seeder sets up
 * the workflow structure with appropriate column names, descriptions,
 * colors, and WIP limits for different types of boards.
 */
class KanbanColumnSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('📊 Creating Kanban columns...');

        // Get boards
        $projectBoard   = Board::where('name', 'Project Management')->first();
        $marketingBoard = Board::where('name', 'Marketing Campaign')->first();

        if ( ! $projectBoard || ! $marketingBoard) {
            $this->command->error('Required boards not found. Please run KanbanBoardSeeder first.');

            return;
        }

        // Create columns for Project Management board
        $projectColumns = [
            [
                'name'        => 'Backlog',
                'description' => 'Tasks that are planned but not yet started',
                'color'       => '#6B7280',
                'order'       => 0,
                'wip_limit'   => null,
            ],
            [
                'name'        => 'To Do',
                'description' => 'Tasks ready to be worked on',
                'color'       => '#F59E0B',
                'order'       => 1,
                'wip_limit'   => 10,
            ],
            [
                'name'        => 'In Progress',
                'description' => 'Tasks currently being worked on',
                'color'       => '#3B82F6',
                'order'       => 2,
                'wip_limit'   => 5,
            ],
            [
                'name'        => 'Review',
                'description' => 'Tasks ready for review',
                'color'       => '#8B5CF6',
                'order'       => 3,
                'wip_limit'   => 8,
            ],
            [
                'name'        => 'Done',
                'description' => 'Completed tasks',
                'color'       => '#10B981',
                'order'       => 4,
                'wip_limit'   => null,
            ],
        ];

        foreach ($projectColumns as $columnData) {
            $projectBoard->columns()->firstOrCreate(
                ['name' => $columnData['name']],
                $columnData
            );
        }

        // Create columns for Marketing Campaign board
        $marketingColumns = [
            [
                'name'        => 'Ideas',
                'description' => 'Marketing campaign ideas and concepts',
                'color'       => '#F59E0B',
                'order'       => 0,
                'wip_limit'   => null,
            ],
            [
                'name'        => 'Planning',
                'description' => 'Campaigns in planning phase',
                'color'       => '#3B82F6',
                'order'       => 1,
                'wip_limit'   => 5,
            ],
            [
                'name'        => 'Active',
                'description' => 'Currently running campaigns',
                'color'       => '#10B981',
                'order'       => 2,
                'wip_limit'   => 3,
            ],
            [
                'name'        => 'Completed',
                'description' => 'Finished campaigns',
                'color'       => '#6B7280',
                'order'       => 3,
                'wip_limit'   => null,
            ],
        ];

        foreach ($marketingColumns as $columnData) {
            $marketingBoard->columns()->firstOrCreate(
                ['name' => $columnData['name']],
                $columnData
            );
        }

        $projectColumnCount   = $projectBoard->columns()->count();
        $marketingColumnCount = $marketingBoard->columns()->count();

        $this->command->info("✅ Created columns for Project Management board ({$projectColumnCount} columns)");
        $this->command->info("✅ Created columns for Marketing Campaign board ({$marketingColumnCount} columns)");
    }
}
