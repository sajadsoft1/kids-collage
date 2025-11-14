<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Single Choice Questions
        Question::factory()
            ->count(20)
            ->singleChoice()
            ->create()
            ->each(function ($question) {
                QuestionOption::factory()->count(4)->create([
                    'question_id' => $question->id,
                ]);

                // یکی رو صحیح می‌کنیم
                $question->options()->inRandomOrder()->limit(1)->update([
                    'is_correct' => true,
                ]);
            });

        // Multiple Choice Questions
        Question::factory()
            ->count(15)
            ->multipleChoice()
            ->create()
            ->each(function ($question) {
                QuestionOption::factory()->count(5)->create([
                    'question_id' => $question->id,
                ]);

                // دو تا رو صحیح می‌کنیم
                $question->options()->inRandomOrder()->limit(2)->update([
                    'is_correct' => true,
                ]);
            });

        // True/False Questions
        Question::factory()
            ->count(10)
            ->trueFalse()
            ->create();

        // survey
        Question::factory()
            ->count(20)
            ->singleChoice()
            ->create([
                'is_survey_question' => true,
            ])
            ->each(function ($question) {
                QuestionOption::factory()->count(4)->create([
                    'question_id' => $question->id,
                ]);

                // یکی رو صحیح می‌کنیم
                $question->options()->inRandomOrder()->limit(1)->update([
                    'is_correct' => true,
                ]);
            });

        // Multiple Choice Questions
        Question::factory()
            ->count(15)
            ->multipleChoice()
            ->create([
                'is_survey_question' => true,
            ])
            ->each(function ($question) {
                QuestionOption::factory()->count(5)->create([
                    'question_id' => $question->id,
                ]);

                // دو تا رو صحیح می‌کنیم
                $question->options()->inRandomOrder()->limit(2)->update([
                    'is_correct' => true,
                ]);
            });
    }
}
