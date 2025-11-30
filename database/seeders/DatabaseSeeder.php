<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding process...');

        // 1. System Defaults - Core system requirements:
        // - RolePermissionSeeder,
        // - AdminSeeder,
        // - SettingSeeder
        $this->call(SystemDefaultsSeeder::class);
        Auth::login(User::find(1));

        // 2. Website Requirements - Essential website functionality:
        // - UserSeeder
        // - BannerSeeder
        // - SliderSeeder
        // - ClientSeeder
        // - TeammateSeeder
        // - ContactUsSeeder
        // - SocialMediaSeeder
        // - TicketSeeder
        // - LicenseSeeder
        $this->call(WebsiteRequirementsSeeder::class);

        // 3. Content
        // - CategorySeeder
        // - BlogSeeder
        // - CommentSeeder
        // - FaqSeeder
        // - OpinionSeeder
        // - BulletinSeeder
        $this->call(ContentSeeder::class);

        // 4. Kanban System — Project management functionality
        // - KanbanUserSeeder
        // - KanbanBoardSeeder
        // - KanbanColumnSeeder
        // - KanbanCardSeeder
        $this->call(KanbanSystemSeeder::class);

        // 5. LMS - Learning Management System
        // - RoomSeeder
        // - CourseSeeder
        // - SessionSeeder
        // - EnrollmentSeeder
        // - AttendanceSeeder
        $this->call(LmsSeeder::class);

        // 6. Financial System — Financial management functionality.
        // - OrderSeeder
        // - PaymentSeeder
        // - InstallmentSeeder
        $this->call(FinancialSeeder::class);

        $this->command->info('🔑 Login credentials: developer@gmail.com / password');

        // 7. Exam System — Exam management functionality.
        $this->call(ExamSeeder::class);

        Artisan::call('optimize:clear');
    }
}
