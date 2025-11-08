<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\QuestionCompetency\StoreQuestionCompetencyAction;
use Illuminate\Database\Seeder;

class QuestionCompetencySeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('seeders/data/karno_exam.php');
        foreach ($data['question_competency'] as $row) {
            StoreQuestionCompetencyAction::run([
                'title'       => $row['title'],
                'description' => $row['description'],
            ]);
        }
    }
}
