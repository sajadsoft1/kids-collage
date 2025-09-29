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
        // Seed core required data first
        $this->call([
            NecessarySeeder::class,
            RolePermissionSeeder::class,
            AdminSeeder::class,
            SettingSeeder::class,
        ]);

        // Kanban and system fixtures; FakeSeeder already includes categories/blog/etc.
        $this->call([
            CourseSystemSeeder::class, // creates default course_template, session_template, term
            FakeSeeder::class,
            KanbanSeeder::class,
            BulletinSeeder::class,
            LicenseSeeder::class,
        ]);

        // Infrastructure for courses
        $this->call([
            RoomSeeder::class,
            // Temporarily skip legacy course/session/enrollment/attendance/order/payments
            // They target old schema (slug/title on courses; sessions on sessions table, etc.)
            // Add minimal compatible seeds here later if needed.
        ]);

        Artisan::call('optimize:clear');
    }
}
