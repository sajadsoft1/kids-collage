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
            RoomSeeder::class,        // Classrooms and learning spaces
            // Note: CourseSeeder, SessionSeeder, EnrollmentSeeder, AttendanceSeeder,
            // OrderSeeder, PaymentSeeder, InstallmentSeeder are temporarily skipped
            // as they target old schema. They can be re-enabled once updated.
        ]);

        $this->command->info('✅ LMS seeded successfully!');
        $this->command->warn('⚠️  Some LMS seeders are temporarily disabled due to schema changes.');
        $this->command->info('💡 To enable full LMS seeding, update the legacy seeders to match new schema.');
    }
}
