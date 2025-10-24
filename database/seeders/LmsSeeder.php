<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * LMS (Learning Management System) Seeder Batch
 *
 * This batch handles all the educational and course management functionality
 * including rooms, courses, sessions, enrollments, attendances, certificates,
 * orders, payments, and installments. This is the core educational system.
 */
class LmsSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('🎓 Seeding LMS (Learning Management System)...');

        $this->call([
            TermSeeder::class,
            RoomSeeder::class,
            CourseSeeder::class,
            ResourceSeeder::class,
            // EnrollmentSeeder::class,
            // AttendanceSeeder::class,
        ]);

        $this->command->info('✅ LMS seeded successfully!');
    }
}
