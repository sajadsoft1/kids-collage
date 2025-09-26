<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Enrollment\StoreEnrollmentAction;
use Exception;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['enrollment'] as $row) {
            $model = StoreEnrollmentAction::run([
                'user_id'     => $row['user_id'],
                'course_id'   => $row['course_id'],
                'enroll_date' => $row['enroll_date'],
                'status'      => $row['status'],
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
