<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\QuestionSubject\StoreQuestionSubjectAction;
use Illuminate\Database\Seeder;

class QuestionSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('seeders/data/karno_exam.php');
        foreach ($data['question_subject'] as $row) {
            StoreQuestionSubjectAction::run([
                'title' => $row['title'],
                'description' => $row['description'],
                'ordering' => $row['ordering'],
                'published' => $row['published'],
            ]);
        }
    }
}
