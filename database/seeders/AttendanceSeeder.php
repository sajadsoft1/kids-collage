<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Attendance\StoreAttendanceAction;
use Exception;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['attendance'] as $row) {
            $model = StoreAttendanceAction::run([
                'enrollment_id' => $row['enrollment_id'],
                'session_id' => $row['session_id'],
                'present' => $row['present'],
                'arrival_time' => $row['arrival_time'],
                'leave_time' => $row['leave_time'],
            ]);

            try {
                $model->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
