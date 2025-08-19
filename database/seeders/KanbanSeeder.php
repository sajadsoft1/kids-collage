<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CardStatusEnum;
use App\Enums\CardTypeEnum;
use App\Enums\PriorityEnum;
use App\Models\Board;
use App\Models\CardHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class KanbanSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Get or create a test user
        $user = User::firstOrCreate(
            ['email' => 'developer@gmail.com'],
            [
                'name'     => 'Admin',
                'family'   => 'User',
                'password' => bcrypt('password'),
                'status'   => 1,
            ]
        );

        // Create a sample board
        $board = Board::create([
            'name'        => 'Project Management',
            'description' => 'Main project management board for tracking tasks and features',
            'color'       => '#3B82F6',
            'is_active'   => true,
        ]);

        // Add user as owner
        $board->users()->attach($user->id, ['role' => 'owner']);

        // Create columns
        $columns = [
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

        foreach ($columns as $columnData) {
            $column = $board->columns()->create($columnData);
        }

        // Create sample cards
        $cards = [
            [
                'title'            => 'Design new user interface',
                'description'      => 'Create wireframes and mockups for the new dashboard design',
                'card_type'        => CardTypeEnum::FEATURE,
                'priority'         => PriorityEnum::HIGH,
                'status'           => CardStatusEnum::ACTIVE,
                'due_date'         => now()->addDays(7),
                'column_id'        => 2, // To Do
                'order'            => 0,
                'extra_attributes' => [
                    'design_tools'    => 'Figma',
                    'target_audience' => 'End users',
                ],
            ],
            [
                'title'            => 'Fix login bug',
                'description'      => 'Users are experiencing issues with the login form validation',
                'card_type'        => CardTypeEnum::BUG,
                'priority'         => PriorityEnum::URGENT,
                'status'           => CardStatusEnum::ACTIVE,
                'due_date'         => now()->addDays(2),
                'column_id'        => 3, // In Progress
                'order'            => 0,
                'extra_attributes' => [
                    'browser_affected' => 'Chrome, Firefox',
                    'error_message'    => 'Invalid credentials',
                ],
            ],
            [
                'title'            => 'Implement user authentication',
                'description'      => 'Add JWT-based authentication system with refresh tokens',
                'card_type'        => CardTypeEnum::TASK,
                'priority'         => PriorityEnum::MEDIUM,
                'status'           => CardStatusEnum::ACTIVE,
                'due_date'         => now()->addDays(14),
                'column_id'        => 1, // Backlog
                'order'            => 0,
                'extra_attributes' => [
                    'technology'     => 'Laravel Sanctum',
                    'security_level' => 'High',
                ],
            ],
            [
                'title'            => 'Client meeting - Q1 Review',
                'description'      => 'Quarterly review meeting with the main client to discuss progress',
                'card_type'        => CardTypeEnum::MEETING,
                'priority'         => PriorityEnum::HIGH,
                'status'           => CardStatusEnum::ACTIVE,
                'due_date'         => now()->addDays(3),
                'column_id'        => 2, // To Do
                'order'            => 1,
                'extra_attributes' => [
                    'meeting_duration' => '2 hours',
                    'meeting_location' => 'Zoom',
                    'attendees'        => ['Client Team', 'Project Manager', 'Tech Lead'],
                ],
            ],
            [
                'title'            => 'Send weekly report',
                'description'      => 'Prepare and send the weekly progress report to stakeholders',
                'card_type'        => CardTypeEnum::EMAIL,
                'priority'         => PriorityEnum::LOW,
                'status'           => CardStatusEnum::ACTIVE,
                'due_date'         => now()->addDays(1),
                'column_id'        => 4, // Review
                'order'            => 0,
                'extra_attributes' => [
                    'email_recipients' => ['stakeholders@company.com'],
                    'report_type'      => 'Weekly Progress',
                ],
            ],
            [
                'title'            => 'Database optimization',
                'description'      => 'Optimize database queries and add proper indexing',
                'card_type'        => CardTypeEnum::TASK,
                'priority'         => PriorityEnum::MEDIUM,
                'status'           => CardStatusEnum::COMPLETED,
                'due_date'         => now()->subDays(5),
                'column_id'        => 5, // Done
                'order'            => 0,
                'extra_attributes' => [
                    'performance_improvement' => '40%',
                    'tables_optimized'        => ['users', 'posts', 'comments'],
                ],
            ],
            [
                'title'            => 'Call with vendor',
                'description'      => 'Discuss pricing and timeline for the new software license',
                'card_type'        => CardTypeEnum::CALL,
                'priority'         => PriorityEnum::MEDIUM,
                'status'           => CardStatusEnum::ACTIVE,
                'due_date'         => now()->addDays(4),
                'column_id'        => 2, // To Do
                'order'            => 2,
                'extra_attributes' => [
                    'phone_number'  => '+1-555-0123',
                    'call_duration' => '30 minutes',
                    'call_notes'    => 'Discuss enterprise pricing',
                ],
            ],
        ];

        foreach ($cards as $cardData) {
            $card = $board->cards()->create($cardData);

            // Assign the user to some cards
            if (in_array($card->card_type, [CardTypeEnum::BUG]) || $card->priority === PriorityEnum::URGENT) {
                $card->users()->attach($user->id, ['role' => 'assignee']);
            }

            // Create history record
            CardHistory::create([
                'card_id'          => $card->id,
                'user_id'          => $user->id,
                'column_id'        => $card->column_id,
                'action'           => 'created',
                'description'      => 'Card created during seeding',
                'extra_attributes' => [
                    'created_by' => $user->id,
                    'created_at' => now()->toISOString(),
                    'seeded'     => true,
                ],
            ]);
        }

        // Create a second board for demonstration
        $board2 = Board::create([
            'name'        => 'Marketing Campaign',
            'description' => 'Board for tracking marketing activities and campaigns',
            'color'       => '#EC4899',
            'is_active'   => true,
        ]);

        // Add user as admin
        $board2->users()->attach($user->id, ['role' => 'admin']);

        // Create columns for marketing board
        $marketingColumns = [
            [
                'name'        => 'Ideas',
                'description' => 'Marketing campaign ideas and concepts',
                'color'       => '#F59E0B',
                'order'       => 0,
            ],
            [
                'name'        => 'Planning',
                'description' => 'Campaigns in planning phase',
                'color'       => '#3B82F6',
                'order'       => 1,
            ],
            [
                'name'        => 'Active',
                'description' => 'Currently running campaigns',
                'color'       => '#10B981',
                'order'       => 2,
            ],
            [
                'name'        => 'Completed',
                'description' => 'Finished campaigns',
                'color'       => '#6B7280',
                'order'       => 3,
            ],
        ];

        foreach ($marketingColumns as $columnData) {
            $board2->columns()->create($columnData);
        }

        $this->command->info('Kanban system seeded successfully!');
        $this->command->info('Created 2 boards with sample columns and cards.');
        $this->command->info('Login with: admin@example.com / password');
    }
}
