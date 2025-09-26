<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Course\StoreCourseAction;
use Exception;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['course'] as $row) {
            $model = StoreCourseAction::run([
                'title'       => $row['title'],
                'description' => $row['description'],
                'body'        => $row['body'],
                'teacher_id'  => $row['teacher_id'],
                'category_id' => $row['category_id'],
                'price'       => $row['price'],
                'type'        => $row['type'],
                'start_date'  => $row['start_date'],
                'end_date'    => $row['end_date'],
                'languages'   => $row['languages'],
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
