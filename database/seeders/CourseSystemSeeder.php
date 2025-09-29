<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TermStatus;
use DB;
use Illuminate\Database\Seeder;

class CourseSystemSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Create a default course template
        DB::table('course_templates')->insert([
            'category_id'   => null,
            'level'         => 'beginner',
            'prerequisites' => null,
            'is_self_paced' => false,
            'languages'     => null,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Create a default session template
        DB::table('session_templates')->insert([
            'course_template_id' => 1,
            'order'              => 1,
            'languages'          => null,
            'duration_minutes'   => 60,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // Create a default term
        DB::table('terms')->insert([
            'languages'  => null,
            'start_date' => now()->startOfMonth(),
            'end_date'   => now()->addMonth()->endOfMonth(),
            'status'     => TermStatus::ACTIVE->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
