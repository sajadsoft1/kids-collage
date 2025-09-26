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
        $this->call([
            NecessarySeeder::class,
            FakeSeeder::class,
            KanbanSeeder::class,
            BulletinSeeder::class,
            LicenseSeeder::class,
            RoomSeeder::class,
            CourseSeeder::class,
            SessionSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class,
            InstallmentSeeder::class,
            EnrollmentSeeder::class,
            AttendanceSeeder::class,
        ]);

        Artisan::call('optimize:clear');
    }
}
