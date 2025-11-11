<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ExamStatusEnum;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class ExamService
{
    /** ایجاد آزمون */
    public function create(array $data): Exam
    {
        $exam = Exam::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'type' => $data['type'],
            'total_score' => $data['total_score'] ?? null,
            'duration' => $data['duration'] ?? null,
            'pass_score' => $data['pass_score'] ?? null,
            'max_attempts' => $data['max_attempts'] ?? null,
            'shuffle_questions' => $data['shuffle_questions'] ?? false,
            'show_results' => $data['show_results'] ?? 'after_submit',
            'allow_review' => $data['allow_review'] ?? true,
            'settings' => $data['settings'] ?? [],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'status' => ExamStatusEnum::DRAFT,
            'created_by' => auth()->id(),
        ]);

        // اتصال تگ‌ها
        if ( ! empty($data['tags'])) {
            $exam->syncTags($data['tags']);
        }

        return $exam;
    }

    /** ویرایش آزمون */
    public function update(Exam $exam, array $data): Exam
    {
        $exam->update([
            'title' => $data['title'] ?? $exam->title,
            'description' => $data['description'] ?? $exam->description,
            'category_id' => $data['category_id'] ?? $exam->category_id,
            'type' => $data['type'] ?? $exam->type,
            'total_score' => $data['total_score'] ?? $exam->total_score,
            'duration' => $data['duration'] ?? $exam->duration,
            'pass_score' => $data['pass_score'] ?? $exam->pass_score,
            'max_attempts' => $data['max_attempts'] ?? $exam->max_attempts,
            'shuffle_questions' => $data['shuffle_questions'] ?? $exam->shuffle_questions,
            'show_results' => $data['show_results'] ?? $exam->show_results,
            'allow_review' => $data['allow_review'] ?? $exam->allow_review,
            'settings' => $data['settings'] ?? $exam->settings,
            'starts_at' => $data['starts_at'] ?? $exam->starts_at,
            'ends_at' => $data['ends_at'] ?? $exam->ends_at,
        ]);

        // به‌روزرسانی تگ‌ها
        if (isset($data['tags'])) {
            $exam->syncTags($data['tags']);
        }

        return $exam->fresh();
    }

    /** حذف آزمون */
    public function delete(Exam $exam): bool
    {
        return DB::transaction(function () use ($exam) {
            // حذف سوالات
            $exam->questions()->detach();

            // حذف تلاش‌ها
            foreach ($exam->attempts as $attempt) {
                $attempt->answers()->delete();
                $attempt->delete();
            }

            // حذف تگ‌ها
            $exam->detachTags($exam->tags);

            return $exam->delete();
        });
    }

    /** اضافه کردن سوال به آزمون */
    public function attachQuestion(
        Exam $exam,
        Question $question,
        float|int|string $weight,
        ?int $order = null,
        ?array $configOverride = null
    ): void {
        $order ??= ($exam->questions()->max('exam_question.order') + 1);

        $exam->questions()->attach($question->id, [
            'weight' => (float) $weight,
            'order' => $order,
            'is_required' => true,
            'config_override' => $configOverride,
        ]);

        $question->incrementUsage();
    }

    /** اضافه کردن چند سوال به آزمون */
    public function attachMultipleQuestions(Exam $exam, array $questions): void
    {
        DB::transaction(function () use ($exam, $questions) {
            foreach ($questions as $index => $questionData) {
                $question = Question::find($questionData['question_id']);

                $this->attachQuestion(
                    exam: $exam,
                    question: $question,
                    weight: $questionData['weight'],
                    order: $questionData['order'] ?? $index + 1,
                    configOverride: $questionData['config_override'] ?? null
                );
            }
        });
    }

    /** حذف سوال از آزمون */
    public function detachQuestion(Exam $exam, Question $question): void
    {
        $exam->questions()->detach($question->id);
        $question->decrementUsage();
    }

    /** به‌روزرسانی وزن سوال */
    public function updateQuestionWeight(Exam $exam, Question $question, float $weight): void
    {
        $exam->questions()->updateExistingPivot($question->id, [
            'weight' => $weight,
        ]);
    }

    /** مرتب‌سازی مجدد سوالات */
    public function reorderQuestions(Exam $exam, array $questionIds): void
    {
        DB::transaction(function () use ($exam, $questionIds) {
            foreach ($questionIds as $order => $questionId) {
                $exam->questions()->updateExistingPivot($questionId, [
                    'order' => $order + 1,
                ]);
            }
        });
    }

    /** انتشار آزمون */
    public function publish(Exam $exam): Exam
    {
        $exam->publish();

        return $exam->fresh();
    }

    /** بایگانی آزمون */
    public function archive(Exam $exam): Exam
    {
        $exam->archive();

        return $exam->fresh();
    }

    /** خروج از بایگانی */
    public function unarchive(Exam $exam): Exam
    {
        $exam->unarchive();

        return $exam->fresh();
    }

    /** کپی آزمون */
    public function duplicate(Exam $exam): Exam
    {
        return DB::transaction(function () use ($exam) {
            $newExam         = $exam->replicate();
            $newExam->title  = $exam->title . ' (کپی)';
            $newExam->status = ExamStatusEnum::DRAFT;
            $newExam->save();

            // کپی سوالات
            foreach ($exam->questions as $question) {
                $newExam->questions()->attach($question->id, [
                    'weight' => $question->pivot->weight,
                    'order' => $question->pivot->order,
                    'is_required' => $question->pivot->is_required,
                    'config_override' => $question->pivot->config_override,
                ]);
            }

            // کپی تگ‌ها
            if ($exam->tags->isNotEmpty()) {
                $newExam->syncTags($exam->tags);
            }

            return $newExam;
        });
    }
}
