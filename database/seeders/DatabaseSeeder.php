<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding process...');
        $this->command->info('');

        // 1. System Defaults - Core system requirements
        $this->call(SystemDefaultsSeeder::class);
        $this->command->info('');

        // 2. Website Requirements - Essential website functionality
        $this->call(WebsiteRequirementsSeeder::class);
        $this->command->info('');

        // 3. Content - Blog posts, categories, and user-generated content
        $this->call(ContentSeeder::class);
        $this->command->info('');

        // 4. Kanban System - Project management functionality
        $this->call(KanbanSystemSeeder::class);
        $this->command->info('');

        // 5. LMS - Learning Management System
        $this->call(LmsSeeder::class);
        $this->command->info('');

        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📋 Summary of seeded data:');
        $this->command->info('   ✅ System defaults (roles, admin user, settings)');
        $this->command->info('   ✅ Website requirements (users, banners, sliders, etc.)');
        $this->command->info('   ✅ Content (blogs, categories, comments, FAQs)');
        $this->command->info('   ✅ Kanban system (project management boards)');
        $this->command->info('   ✅ LMS infrastructure (rooms, course templates)');
        $this->command->info('');
        $this->command->info('🔑 Login credentials: admin@example.com / password');

        Artisan::call('optimize:clear');
    }
}
