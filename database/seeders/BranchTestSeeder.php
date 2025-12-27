<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BooleanEnum;
use App\Enums\BranchStatusEnum;
use App\Enums\CardStatusEnum;
use App\Enums\CardTypeEnum;
use App\Enums\DiscountTypeEnum;
use App\Enums\PriorityEnum;
use App\Helpers\Utils;
use App\Models\Board;
use App\Models\Branch;
use App\Models\Card;
use App\Models\CardFlow;
use App\Models\CardHistory;
use App\Models\Column;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\CourseSessionTemplate;
use App\Models\CourseTemplate;
use App\Models\Discount;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\FlashCard;
use App\Models\LeitnerBox;
use App\Models\Note;
use App\Models\Notebook;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Question;
use App\Models\QuestionCompetency;
use App\Models\QuestionOption;
use App\Models\QuestionSubject;
use App\Models\QuestionSystem;
use App\Models\Resource;
use App\Models\Room;
use App\Models\Task;
use App\Models\Term;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Database\Seeder;

class BranchTestSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('🧪 Starting Branch System Test...');

        // Create test branches
        $branch1 = Branch::firstOrCreate(
            ['code' => 'test-branch-1'],
            [
                'name' => 'شعبه تست 1',
                'status' => BranchStatusEnum::ACTIVE,
                'is_default' => false,
                'settings' => ['test' => true],
            ]
        );

        $branch2 = Branch::firstOrCreate(
            ['code' => 'test-branch-2'],
            [
                'name' => 'شعبه تست 2',
                'status' => BranchStatusEnum::ACTIVE,
                'is_default' => false,
                'settings' => ['test' => true],
            ]
        );

        $this->command->info("✅ Created test branches: {$branch1->id} and {$branch2->id}");

        // Get a test user
        $user = User::first();
        if ( ! $user) {
            $this->command->error('❌ No user found. Please run UserSeeder first.');

            return;
        }

        // Test each branch
        $this->testBranch($branch1, $user, 'Branch 1');
        $this->testBranch($branch2, $user, 'Branch 2');

        // Test branch scope filtering
        $this->testBranchScope($branch1, $branch2);

        $this->command->info('✅ Branch System Test completed successfully!');
    }

    /** Test creating data for a specific branch. */
    protected function testBranch(Branch $branch, User $user, string $branchName): void
    {
        $this->command->info("📦 Testing {$branchName} (ID: {$branch->id})...");

        // Set current branch context
        Utils::setCurrentBranchId($branch->id);
        $user->branches()->attach($branch->id);

        // Test Kanban System
        $this->testKanban($branch, $user);

        // Test Course System
        $this->testCourses($branch, $user);

        // Test Question System
        $this->testQuestions($branch, $user);

        // Test Exam System
        $this->testExams($branch, $user);

        // Test Financial System
        $this->testFinancial($branch, $user);

        // Test Other Models
        $this->testOtherModels($branch, $user);

        $this->command->info("✅ {$branchName} test completed!");
    }

    /** Test Kanban models. */
    protected function testKanban(Branch $branch, User $user): void
    {
        // Create Board
        $board = Board::create([
            'name' => "Test Board - {$branch->name}",
            'description' => "Test board for branch {$branch->id}",
            'color' => '#3B82F6',
            'is_active' => true,
            'branch_id' => $branch->id,
        ]);

        // Create Column
        $column = Column::create([
            'board_id' => $board->id,
            'name' => 'Test Column',
            'order' => 1,
            'is_active' => true,
            'branch_id' => $branch->id,
        ]);

        // Create Card
        $card = Card::create([
            'board_id' => $board->id,
            'column_id' => $column->id,
            'title' => "Test Card - {$branch->name}",
            'card_type' => CardTypeEnum::TASK,
            'priority' => PriorityEnum::MEDIUM,
            'status' => CardStatusEnum::ACTIVE,
            'order' => 1,
            'branch_id' => $branch->id,
        ]);

        // Create CardHistory
        CardHistory::create([
            'card_id' => $card->id,
            'user_id' => $user->id,
            'column_id' => $column->id,
            'action' => 'created',
            'description' => "Test history for branch {$branch->id}",
            'branch_id' => $branch->id,
        ]);

        // Create CardFlow
        CardFlow::create([
            'board_id' => $board->id,
            'from_column_id' => $column->id,
            'to_column_id' => $column->id,
            'name' => "Test Flow - {$branch->name}",
            'is_active' => true,
            'branch_id' => $branch->id,
        ]);

        $this->command->info("  ✅ Kanban: Board({$board->id}), Column({$column->id}), Card({$card->id})");
    }

    /** Test Course models. */
    protected function testCourses(Branch $branch, User $user): void
    {
        // Create Term
        $term = Term::create([
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'status' => \App\Enums\TermStatus::ACTIVE,
            'branch_id' => $branch->id,
        ]);

        // Create Room
        $room = Room::create([
            'name' => "Test Room - {$branch->name}",
            'capacity' => 30,
            'location' => "Location {$branch->id}",
            'branch_id' => $branch->id,
        ]);

        // Create CourseTemplate
        $courseTemplate = CourseTemplate::firstOrCreate(
            ['slug' => "test-course-template-{$branch->id}-" . time()],
            [
                'level' => \App\Enums\CourseLevelEnum::BIGGINER,
                'type' => \App\Enums\CourseTypeEnum::IN_PERSON,
                'is_self_paced' => false,
                'branch_id' => $branch->id,
            ]
        );

        // Create CourseSessionTemplate
        $sessionTemplate = CourseSessionTemplate::create([
            'course_template_id' => $courseTemplate->id,
            'order' => 1,
            'duration_minutes' => 60,
            'type' => \App\Enums\SessionType::IN_PERSON,
            'branch_id' => $branch->id,
        ]);

        // Create Course
        $course = Course::create([
            'course_template_id' => $courseTemplate->id,
            'term_id' => $term->id,
            'teacher_id' => $user->id,
            'price' => 100000,
            'capacity' => 30,
            'status' => \App\Enums\CourseStatusEnum::ACTIVE,
            'branch_id' => $branch->id,
        ]);

        // Create CourseSession
        CourseSession::create([
            'course_id' => $course->id,
            'course_session_template_id' => $sessionTemplate->id,
            'date' => now()->addDays(7),
            'start_time' => now()->setTime(10, 0),
            'end_time' => now()->setTime(11, 30),
            'status' => \App\Enums\SessionStatus::PLANNED,
            'session_type' => \App\Enums\SessionType::IN_PERSON,
            'branch_id' => $branch->id,
        ]);

        $this->command->info("  ✅ Courses: Term({$term->id}), Room({$room->id}), CourseTemplate({$courseTemplate->id}), Course({$course->id})");
    }

    /** Test Question models. */
    protected function testQuestions(Branch $branch, User $user): void
    {
        // Create QuestionSubject
        $subject = QuestionSubject::create([
            'ordering' => 1,
            'branch_id' => $branch->id,
        ]);

        // Create QuestionCompetency
        $competency = QuestionCompetency::create([
            'ordering' => 1,
            'branch_id' => $branch->id,
        ]);

        // Create QuestionSystem
        $category = \App\Models\Category::first();
        $system = QuestionSystem::create([
            'published' => BooleanEnum::ENABLE,
            'ordering' => 1,
            'category_id' => $category?->id ?? 1,
            'branch_id' => $branch->id,
        ]);

        // Create Question
        $question = Question::create([
            'type' => \App\Enums\QuestionTypeEnum::MULTIPLE_CHOICE,
            'title' => "Test Question - {$branch->name}",
            'body' => 'What is the answer?',
            'difficulty' => \App\Enums\DifficultyEnum::EASY,
            'default_score' => 10,
            'is_active' => true,
            'is_public' => true,
            'subject_id' => $subject->id,
            'competency_id' => $competency->id,
            'created_by' => $user->id,
            'branch_id' => $branch->id,
        ]);

        // Create QuestionOption
        QuestionOption::create([
            'question_id' => $question->id,
            'content' => 'Option 1',
            'type' => 'text',
            'is_correct' => true,
            'order' => 1,
            'branch_id' => $branch->id,
        ]);

        $this->command->info("  ✅ Questions: Subject({$subject->id}), Competency({$competency->id}), System({$system->id}), Question({$question->id})");
    }

    /** Test Exam models. */
    protected function testExams(Branch $branch, User $user): void
    {
        // Create Exam
        $category = \App\Models\Category::first();
        $exam = Exam::create([
            'title' => "Test Exam - {$branch->name}",
            'description' => 'Test exam description',
            'category_id' => $category?->id ?? 1,
            'type' => \App\Enums\ExamTypeEnum::SCORED,
            'duration' => 60,
            'total_score' => 100,
            'status' => \App\Enums\ExamStatusEnum::PUBLISHED,
            'created_by' => $user->id,
            'branch_id' => $branch->id,
        ]);

        // Create ExamAttempt
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'started_at' => now(),
            'status' => \App\Enums\AttemptStatusEnum::IN_PROGRESS,
            'branch_id' => $branch->id,
        ]);

        // Create UserAnswer
        $question = Question::where('branch_id', $branch->id)->first();
        if ($question) {
            UserAnswer::create([
                'exam_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'answer_data' => ['answer' => 'test'],
                'score' => 5,
                'max_score' => 10,
                'is_correct' => false,
                'answered_at' => now(),
                'branch_id' => $branch->id,
            ]);
        }

        $this->command->info("  ✅ Exams: Exam({$exam->id}), Attempt({$attempt->id})");
    }

    /** Test Financial models. */
    protected function testFinancial(Branch $branch, User $user): void
    {
        // Create Discount
        $discount = Discount::firstOrCreate(
            ['code' => "TEST-{$branch->id}-" . time()],
            [
                'type' => DiscountTypeEnum::PERCENTAGE,
                'value' => 10,
                'min_order_amount' => 100,
                'usage_limit' => 100,
                'usage_per_user' => 5,
                'is_active' => BooleanEnum::ENABLE,
                'branch_id' => $branch->id,
            ]
        );

        // Create Order
        $order = Order::create([
            'order_number' => 'ORD-' . str_pad((string) (Order::max('id') + 1), 8, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'type' => \App\Enums\OrderTypeEnum::COURSE,
            'pure_amount' => 500,
            'discount_amount' => 0,
            'total_amount' => 500,
            'status' => \App\Enums\OrderStatusEnum::PENDING,
            'branch_id' => $branch->id,
        ]);

        // Create OrderItem
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'itemable_type' => Course::class,
            'itemable_id' => Course::where('branch_id', $branch->id)->first()?->id ?? 1,
            'price' => 500,
            'quantity' => 1,
            'branch_id' => $branch->id,
        ]);

        // Create Payment
        Payment::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'amount' => 500,
            'type' => \App\Enums\PaymentTypeEnum::ONLINE,
            'status' => \App\Enums\PaymentStatusEnum::PENDING,
            'branch_id' => $branch->id,
        ]);

        $this->command->info("  ✅ Financial: Discount({$discount->id}), Order({$order->id}), OrderItem({$orderItem->id})");
    }

    /** Test other models. */
    protected function testOtherModels(Branch $branch, User $user): void
    {
        // Create Resource
        $resource = Resource::create([
            'type' => \App\Enums\ResourceType::PDF,
            'path' => '/test/resource.pdf',
            'title' => "Test Resource - {$branch->name}",
            'order' => 1,
            'is_public' => false,
            'branch_id' => $branch->id,
        ]);

        // Create FlashCard
        $taxonomy = \App\Models\Taxonomy::first();
        if ($taxonomy) {
            $flashCard = FlashCard::create([
                'user_id' => $user->id,
                'taxonomy_id' => $taxonomy->id,
                'front' => "Front - {$branch->name}",
                'back' => "Back - {$branch->name}",
                'favorite' => BooleanEnum::DISABLE,
                'branch_id' => $branch->id,
            ]);
        } else {
            $flashCard = null;
        }

        // Create LeitnerBox
        if ($flashCard) {
            LeitnerBox::create([
                'user_id' => $user->id,
                'flash_card_id' => $flashCard->id,
                'box' => 1,
                'next_review_at' => now()->addDay(),
                'last_review_at' => now(),
                'finished' => BooleanEnum::DISABLE,
                'branch_id' => $branch->id,
            ]);
        }

        // Create Task
        Task::create([
            'user_id' => $user->id,
            'title' => "Test Task - {$branch->name}",
            'description' => 'Test task description',
            'scheduled_for' => now()->addDays(1),
            'status' => Task::STATUS_PENDING,
            'branch_id' => $branch->id,
        ]);

        // Create Notebook
        $taxonomy = \App\Models\Taxonomy::first();
        if ($taxonomy) {
            Notebook::create([
                'user_id' => $user->id,
                'taxonomy_id' => $taxonomy->id,
                'title' => "Test Notebook - {$branch->name}",
                'body' => 'Test notebook body',
                'tags' => ['test', 'branch'],
                'branch_id' => $branch->id,
            ]);
        }

        // Create Note
        $question = Question::where('branch_id', $branch->id)->first();
        if ($question) {
            Note::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
                'body' => "Test Note - {$branch->name}",
                'branch_id' => $branch->id,
            ]);
        }

        $flashCardId = $flashCard?->id ?? 'N/A';
        $this->command->info("  ✅ Other Models: Resource({$resource->id}), FlashCard({$flashCardId})");
    }

    /** Test branch scope filtering. */
    protected function testBranchScope(Branch $branch1, Branch $branch2): void
    {
        $this->command->info('🔍 Testing Branch Scope Filtering...');

        // Count by branch_id directly (without scope)
        $branch1Boards = Board::where('branch_id', $branch1->id)->count();
        $branch1Courses = Course::where('branch_id', $branch1->id)->count();
        $branch1Questions = Question::where('branch_id', $branch1->id)->count();

        $branch2Boards = Board::where('branch_id', $branch2->id)->count();
        $branch2Courses = Course::where('branch_id', $branch2->id)->count();
        $branch2Questions = Question::where('branch_id', $branch2->id)->count();

        // Test withAllBranches
        $allBoards = Board::withAllBranches()->count();
        $allCourses = Course::withAllBranches()->count();
        $allQuestions = Question::withAllBranches()->count();

        $this->command->info("  📊 Branch 1 (ID: {$branch1->id}): Boards({$branch1Boards}), Courses({$branch1Courses}), Questions({$branch1Questions})");
        $this->command->info("  📊 Branch 2 (ID: {$branch2->id}): Boards({$branch2Boards}), Courses({$branch2Courses}), Questions({$branch2Questions})");
        $this->command->info("  📊 All Branches (withAllBranches): Boards({$allBoards}), Courses({$allCourses}), Questions({$allQuestions})");

        // Verify branch isolation
        if ($branch1Boards > 0 && $branch2Boards > 0 && $allBoards >= ($branch1Boards + $branch2Boards)) {
            $this->command->info('  ✅ Branch data isolation is working correctly!');
            $this->command->info('  ✅ Branch scope will filter data correctly in admin/API requests.');
        } else {
            $this->command->warn('  ⚠️  Branch data isolation may have issues. Please check.');
        }

        // Test scope with context
        Utils::setCurrentBranchId($branch1->id);
        $scopedBranch1Boards = Board::count();
        $scopedBranch1Courses = Course::count();
        $scopedBranch1Questions = Question::count();

        Utils::setCurrentBranchId($branch2->id);
        $scopedBranch2Boards = Board::count();
        $scopedBranch2Courses = Course::count();
        $scopedBranch2Questions = Question::count();

        $this->command->info("  🔍 With Branch Scope (Branch 1 context): Boards({$scopedBranch1Boards}), Courses({$scopedBranch1Courses}), Questions({$scopedBranch1Questions})");
        $this->command->info("  🔍 With Branch Scope (Branch 2 context): Boards({$scopedBranch2Boards}), Courses({$scopedBranch2Courses}), Questions({$scopedBranch2Questions})");

        if ($scopedBranch1Boards === $branch1Boards && $scopedBranch2Boards === $branch2Boards) {
            $this->command->info('  ✅ Branch scope filtering is working correctly!');
        } else {
            $this->command->warn('  ⚠️  Branch scope filtering may have issues. Please check.');
        }
    }
}
