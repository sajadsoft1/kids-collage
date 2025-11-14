<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\QuestionTypeEnum;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;

class QuestionService
{
    /** ایجاد سوال جدید */
    public function create(array $data): Question
    {
        return DB::transaction(function () use ($data) {
            // ایجاد سوال
            $question = new Question([
                'type' => $data['type'],
                'category_id' => $data['category_id'] ?? null,
                'subject_id' => $data['subject_id'] ?? null,
                'competency_id' => $data['competency_id'] ?? null,
                'title' => $data['title'],
                'body' => $data['body'] ?? null,
                'explanation' => $data['explanation'] ?? null,
                'difficulty' => $data['difficulty'] ?? null,
                'default_score' => $data['default_score'] ?? 1,
                'config' => $data['config'] ?? [],
                'correct_answer' => $data['correct_answer'] ?? null,
                'metadata' => $data['metadata'] ?? [],
                'created_by' => auth()->id(),
                'is_active' => $data['is_active'] ?? true,
                'is_public' => $data['is_public'] ?? false,
            ]);

            // اعتبارسنجی با Type Handler
            $validated = $question->validateData($data);

            $question->save();

            // ذخیره گزینه‌ها (اگر نیاز باشد)
            if ($this->typeNeedsOptions($question->type) && ! empty($data['options'])) {
                $this->saveOptions($question, $data['options']);
            }

            // اتصال تگ‌ها
            if ( ! empty($data['tags'])) {
                $question->syncTags($data['tags']);
            }

            return $question->fresh(['options', 'tags']);
        });
    }

    /** ویرایش سوال */
    public function update(Question $question, array $data): Question
    {
        return DB::transaction(function () use ($question, $data) {
            // به‌روزرسانی فیلدها
            $question->fill([
                'title' => $data['title'] ?? $question->title,
                'body' => $data['body'] ?? $question->body,
                'explanation' => $data['explanation'] ?? $question->explanation,
                'difficulty' => $data['difficulty'] ?? $question->difficulty,
                'default_score' => $data['default_score'] ?? $question->default_score,
                'config' => $data['config'] ?? $question->config,
                'correct_answer' => $data['correct_answer'] ?? $question->correct_answer,
                'metadata' => $data['metadata'] ?? $question->metadata,
            ]);

            // اعتبارسنجی
            $validated = $question->validateData($data);

            $question->save();

            // به‌روزرسانی گزینه‌ها
            if (isset($data['options']) && $this->typeNeedsOptions($question->type)) {
                $question->options()->delete();
                $this->saveOptions($question, $data['options']);
            }

            // به‌روزرسانی تگ‌ها
            if (isset($data['tags'])) {
                $question->syncTags($data['tags']);
            }

            return $question->fresh(['options', 'tags']);
        });
    }

    /** حذف سوال */
    public function delete(Question $question): bool
    {
        return DB::transaction(function () use ($question) {
            // حذف از آزمون‌ها
            $question->exams()->detach();

            // حذف گزینه‌ها
            $question->options()->delete();

            // حذف تگ‌ها
            $question->detachTags($question->tags);

            // حذف سوال
            return $question->delete();
        });
    }

    /** کپی سوال */
    public function duplicate(Question $question): Question
    {
        return $question->duplicate();
    }

    /** فعال/غیرفعال کردن سوال */
    public function toggleActive(Question $question): Question
    {
        $question->update(['is_active' => ! $question->is_active]);

        return $question->fresh();
    }

    /** عمومی/خصوصی کردن سوال */
    public function togglePublic(Question $question): Question
    {
        $question->update(['is_public' => ! $question->is_public]);

        return $question->fresh();
    }

    /** ذخیره گزینه‌ها */
    protected function saveOptions(Question $question, array $options): void
    {
        foreach ($options as $index => $option) {
            QuestionOption::create([
                'question_id' => $question->id,
                'content' => $option['content'],
                'type' => $option['type'] ?? 'text',
                'is_correct' => $option['is_correct'] ?? false,
                'order' => $option['order'] ?? $index + 1,
                'metadata' => $option['metadata'] ?? null,
            ]);
        }
    }

    /** آیا نوع سوال نیاز به گزینه دارد؟ */
    protected function typeNeedsOptions(QuestionTypeEnum $type): bool
    {
        return $type->needsOptions();
    }

    /** دریافت سوالات با فیلتر */
    public function getQuestions(array $filters = [], int $perPage = 15)
    {
        return Question::query()
            ->with(['category', 'subject', 'competency', 'creator', 'tags'])
            ->withFilters($filters)
            ->latest()
            ->paginate($perPage);
    }

    /** جستجو در سوالات */
    public function search(string $query, array $filters = [], int $perPage = 15)
    {
        return Question::query()
            ->with(['category', 'subject', 'competency', 'tags'])
            ->search($query)
            ->withFilters($filters)
            ->latest()
            ->paginate($perPage);
    }
}
