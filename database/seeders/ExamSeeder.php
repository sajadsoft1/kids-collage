<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use App\Services\ExamService;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(ExamService $examService): void
    {
        // ایجاد آزمون‌های نمونه
        Exam::factory()
            ->count(5)
            ->published()
            ->scored()
            ->create()
            ->each(function ($exam) use ($examService) {
                // اضافه کردن 10-20 سوال تصادفی
                $questions = Question::active()
                    ->inRandomOrder()
                    ->limit(rand(10, 20))
                    ->get();

                $totalScore = 0;

                foreach ($questions as $index => $question) {
                    $weight = $question->default_score;
                    $totalScore += $weight;

                    $examService->attachQuestion(
                        $exam,
                        $question,
                        $weight,
                        $index + 1
                    );
                }

                // به‌روزرسانی نمره کل
                $exam->update(['total_score' => $totalScore]);
            });

        // آزمون‌های survey
        Exam::factory()
            ->count(3)
            ->published()
            ->survey()
            ->create()
            ->each(function ($exam) use ($examService) {
                $questions = Question::active()
                    ->inRandomOrder()
                    ->limit(rand(5, 10))
                    ->get();

                foreach ($questions as $index => $question) {
                    $examService->attachQuestion(
                        $exam,
                        $question,
                        0, // نمره‌ای ندارد
                        $index + 1
                    );
                }
            });
    }
}
