<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Session\StoreSessionAction;
use Exception;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['session'] as $row) {
            $model = StoreSessionAction::run([
                'title'          => $row['title'],
                'description'    => $row['description'],
                'body'           => $row['body'],
                'course_id'      => $row['course_id'],
                'teacher_id'     => $row['teacher_id'],
                'start_time'     => $row['start_time'],
                'end_time'       => $row['end_time'],
                'room_id'        => $row['room_id'],
                'meeting_link'   => $row['meeting_link'],
                'session_number' => $row['session_number'],
                'languages'      => $row['languages'],
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
