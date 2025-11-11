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

/**
 * Kanban Card Seeder
 *
 * Creates sample cards for existing Kanban boards and columns.
 * This seeder populates the boards with realistic task examples
 * including different card types, priorities, and statuses.
 */
class KanbanCardSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('🎯 Creating Kanban cards...');

        // Get boards and users
        $projectBoard   = Board::where('name', 'Project Management')->first();
        $marketingBoard = Board::where('name', 'Marketing Campaign')->first();
        $developer      = User::where('email', 'developer@gmail.com')->first();

        if ( ! $projectBoard || ! $marketingBoard || ! $developer) {
            $this->command->error('Required boards or users not found. Please run previous seeders first.');

            return;
        }

        // Create cards for Project Management board
        $this->createProjectCards($projectBoard, $developer);

        // Create cards for Marketing Campaign board
        $this->createMarketingCards($marketingBoard, $developer);

        $projectCardCount   = $projectBoard->cards()->count();
        $marketingCardCount = $marketingBoard->cards()->count();

        $this->command->info("✅ Created cards for Project Management board ({$projectCardCount} cards)");
        $this->command->info("✅ Created cards for Marketing Campaign board ({$marketingCardCount} cards)");
    }

    /** Create cards for the Project Management board */
    private function createProjectCards(Board $board, User $user): void
    {
        $projectCards = [
            [
                'title' => 'Design new user interface',
                'description' => 'Create wireframes and mockups for the new dashboard design',
                'card_type' => CardTypeEnum::FEATURE,
                'priority' => PriorityEnum::HIGH,
                'status' => CardStatusEnum::ACTIVE,
                'due_date' => now()->addDays(7),
                'column_name' => 'To Do',
                'order' => 0,
                'extra_attributes' => [
                    'design_tools' => 'Figma',
                    'target_audience' => 'End users',
                ],
            ],
            [
                'title' => 'Fix login bug',
                'description' => 'Users are experiencing issues with the login form validation',
                'card_type' => CardTypeEnum::BUG,
                'priority' => PriorityEnum::URGENT,
                'status' => CardStatusEnum::ACTIVE,
                'due_date' => now()->addDays(2),
                'column_name' => 'In Progress',
                'order' => 0,
                'extra_attributes' => [
                    'browser_affected' => 'Chrome, Firefox',
                    'error_message' => 'Invalid credentials',
                ],
            ],
            [
                'title' => 'Implement user authentication',
                'description' => 'Add JWT-based authentication system with refresh tokens',
                'card_type' => CardTypeEnum::TASK,
                'priority' => PriorityEnum::MEDIUM,
                'status' => CardStatusEnum::ACTIVE,
                'due_date' => now()->addDays(14),
                'column_name' => 'Backlog',
                'order' => 0,
                'extra_attributes' => [
                    'technology' => 'Laravel Sanctum',
                    'security_level' => 'High',
                ],
            ],
            [
                'title' => 'Client meeting - Q1 Review',
                'description' => 'Quarterly review meeting with the main client to discuss progress',
                'card_type' => CardTypeEnum::MEETING,
                'priority' => PriorityEnum::HIGH,
                'status' => CardStatusEnum::ACTIVE,
                'due_date' => now()->addDays(3),
                'column_name' => 'To Do',
                'order' => 1,
                'extra_attributes' => [
                    'meeting_duration' => '2 hours',
                    'meeting_location' => 'Zoom',
                    'attendees' => ['Client Team', 'Project Manager', 'Tech Lead'],
                ],
            ],
            [
                'title' => 'Send weekly report',
                'description' => 'Prepare and send the weekly progress report to stakeholders',
                'card_type' => CardTypeEnum::EMAIL,
                'priority' => PriorityEnum::LOW,
                'status' => CardStatusEnum::ACTIVE,
                'due_date' => now()->addDays(1),
                'column_name' => 'Review',
                'order' => 0,
                'extra_attributes' => [
                    'email_recipients' => ['stakeholders@company.com'],
                    'report_type' => 'Weekly Progress',
                ],
            ],
            [
                'title' => 'Database optimization',
                'description' => 'Optimize database queries and add proper indexing',
                'card_type' => CardTypeEnum::TASK,
                'priority' => PriorityEnum::MEDIUM,
                'status' => CardStatusEnum::COMPLETED,
                'due_date' => now()->subDays(5),
                'column_name' => 'Done',
                'order' => 0,
                'extra_attributes' => [
                    'performance_improvement' => '40%',
                    'tables_optimized' => ['users', 'posts', 'comments'],
                ],
            ],
            [
                'title' => 'Call with vendor',
                'description' => 'Discuss pricing and timeline for the new software license',
                'card_type' => CardTypeEnum::CALL,
                'priority' => PriorityEnum::MEDIUM,
                'status' => CardStatusEnum::ACTIVE,
                'due_date' => now()->addDays(4),
                'column_name' => 'To Do',
                'order' => 2,
                'extra_attributes' => [
                    'phone_number' => '+1-555-0123',
                    'call_duration' => '30 minutes',
                    'call_notes' => 'Discuss enterprise pricing',
                ],
            ],
        ];

        $this->createCardsForBoard($board, $projectCards, $user);
    }

    /** Create cards for the Marketing Campaign board */
    private function createMarketingCards(Board $board, User $user): void
    {
        $marketingCards = [
            [
                'title' => 'Social Media Campaign - Q1',
                'description' => 'Launch comprehensive social media campaign for Q1 product launch',
                'card_type' => CardTypeEnum::TASK,
                'priority' => PriorityEnum::HIGH,
                'status' => CardStatusEnum::ACTIVE,
                'due_date' => now()->addDays(10),
                'column_name' => 'Planning',
                'order' => 0,
                'extra_attributes' => [
                    'platforms' => ['Facebook', 'Instagram', 'Twitter'],
                    'budget' => '$5000',
                    'target_reach' => '50,000 users',
                ],
            ],
            [
                'title' => 'Email Newsletter Design',
                'description' => 'Design and create monthly newsletter template',
                'card_type' => CardTypeEnum::TASK,
                'priority' => PriorityEnum::MEDIUM,
                'status' => CardStatusEnum::ACTIVE,
                'due_date' => now()->addDays(5),
                'column_name' => 'Ideas',
                'order' => 0,
                'extra_attributes' => [
                    'design_tool' => 'Canva',
                    'subscriber_count' => '10,000',
                ],
            ],
            [
                'title' => 'Influencer Partnership',
                'description' => 'Negotiate partnership with tech influencers for product promotion',
                'card_type' => CardTypeEnum::TASK,
                'priority' => PriorityEnum::HIGH,
                'status' => CardStatusEnum::COMPLETED,
                'due_date' => now()->subDays(3),
                'column_name' => 'Completed',
                'order' => 0,
                'extra_attributes' => [
                    'influencers' => ['TechGuru', 'StartupExpert'],
                    'partnership_type' => 'Sponsored content',
                ],
            ],
        ];

        $this->createCardsForBoard($board, $marketingCards, $user);
    }

    /** Create cards for a specific board */
    private function createCardsForBoard(Board $board, array $cardsData, User $user): void
    {
        foreach ($cardsData as $cardData) {
            // Find the column by name
            $column = $board->columns()->where('name', $cardData['column_name'])->first();

            if ( ! $column) {
                $this->command->warn("Column '{$cardData['column_name']}' not found for board '{$board->name}'");

                continue;
            }

            // Remove column_name from card data
            unset($cardData['column_name']);
            $cardData['column_id'] = $column->id;

            // Create the card
            $card = $board->cards()->create($cardData);

            // Assign user to urgent/bug cards
            if (in_array($card->card_type, [CardTypeEnum::BUG]) || $card->priority === PriorityEnum::URGENT) {
                $card->users()->attach($user->id, ['role' => 'assignee']);
            }

            // Create history record
            CardHistory::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'column_id' => $card->column_id,
                'action' => 'created',
                'description' => 'Card created during seeding',
                'extra_attributes' => [
                    'created_by' => $user->id,
                    'created_at' => now()->toISOString(),
                    'seeded' => true,
                ],
            ]);
        }
    }
}
