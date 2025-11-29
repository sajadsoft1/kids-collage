<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Exam\StoreExamAction;
use App\Actions\Question\StoreQuestionAction;
use App\Actions\QuestionCompetency\StoreQuestionCompetencyAction;
use App\Actions\QuestionSubject\StoreQuestionSubjectAction;
use App\Enums\QuestionTypeEnum;
use App\Models\Question;
use App\Models\QuestionCompetency;
use App\Models\QuestionSubject;
use App\Services\ExamService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ExamSeeder extends Seeder
{
    public function __construct(
        private readonly ExamService $examService,
        private readonly StoreExamAction $storeExamAction,
        private readonly StoreQuestionAction $storeQuestionAction,
    ) {}

    public function run(): void
    {
        // Login as admin for created_by
        Auth::loginUsingId(1);

        // Load English exam data
        $englishData = require database_path('seeders/data/english_exam.php');

        // Create competencies and subjects for English
        $competencyMap = $this->createCompetencies($englishData['question_competency']);
        $subjectMap = $this->createSubjects($englishData['question_subject']);

        // Get the subject ID for English
        $englishSubjectId = $subjectMap['English Language'] ?? QuestionSubject::first()?->id;

        // Get competency IDs
        $grammarCompetencyId = $competencyMap['English Grammar'] ?? QuestionCompetency::first()?->id;
        $vocabularyCompetencyId = $competencyMap['English Vocabulary'] ?? $grammarCompetencyId;

        // Create Level 1 questions and exam
        $level1Questions = $this->createQuestions(
            $englishData['questions_level_1'],
            $englishSubjectId,
            $grammarCompetencyId,
            $vocabularyCompetencyId
        );

        // Create Level 2 questions and exam
        $level2Questions = $this->createQuestions(
            $englishData['questions_level_2'],
            $englishSubjectId,
            $grammarCompetencyId,
            $vocabularyCompetencyId
        );

        // Create exams and attach questions
        $this->createExamWithQuestions($englishData['exams'][0], $level1Questions);
        $this->createExamWithQuestions($englishData['exams'][1], $level2Questions);

        Auth::logout();
    }

    /**
     * Create question competencies from data array.
     *
     * @param  array<int, array{title: string, description: string}> $competencies
     * @return array<string, int>                                    Map of title to ID
     */
    protected function createCompetencies(array $competencies): array
    {
        $map = [];

        foreach ($competencies as $competencyData) {
            $existingCompetency = QuestionCompetency::whereHas('translations', function ($query) use ($competencyData) {
                $query->where('key', 'title')
                    ->where('value', $competencyData['title']);
            })->first();

            if ($existingCompetency) {
                $map[$competencyData['title']] = $existingCompetency->id;

                continue;
            }

            $competency = StoreQuestionCompetencyAction::run($competencyData);
            $map[$competencyData['title']] = $competency->id;
        }

        return $map;
    }

    /**
     * Create question subjects from data array.
     *
     * @param  array<int, array{title: string, description: string, ordering: int, published: int}> $subjects
     * @return array<string, int>                                                                   Map of title to ID
     */
    protected function createSubjects(array $subjects): array
    {
        $map = [];

        foreach ($subjects as $subjectData) {
            $existingSubject = QuestionSubject::whereHas('translations', function ($query) use ($subjectData) {
                $query->where('key', 'title')
                    ->where('value', $subjectData['title']);
            })->first();

            if ($existingSubject) {
                $map[$subjectData['title']] = $existingSubject->id;

                continue;
            }

            $subject = StoreQuestionSubjectAction::run($subjectData);
            $map[$subjectData['title']] = $subject->id;
        }

        return $map;
    }

    /**
     * Create questions from data array.
     *
     * @param  array<int, array>    $questionsData
     * @return array<int, Question>
     */
    protected function createQuestions(
        array $questionsData,
        ?int $subjectId,
        ?int $grammarCompetencyId,
        ?int $vocabularyCompetencyId
    ): array {
        $questions = [];

        foreach ($questionsData as $index => $questionData) {
            // Extract options before creating question
            $options = $questionData['options'] ?? [];
            unset($questionData['options']);

            // Set subject and competency
            $questionData['subject_id'] = $subjectId;

            // Alternate between grammar and vocabulary competency based on question content
            $isVocabulary = str_contains(strtolower($questionData['title']), 'mean')
                || str_contains(strtolower($questionData['title']), 'opposite')
                || str_contains(strtolower($questionData['title']), 'colors')
                || str_contains(strtolower($questionData['title']), 'body parts')
                || str_contains(strtolower($questionData['title']), 'fruits')
                || str_contains(strtolower($questionData['title']), 'adverbs')
                || str_contains(strtolower($questionData['title']), 'vocabulary');

            $questionData['competency_id'] = $isVocabulary ? $vocabularyCompetencyId : $grammarCompetencyId;

            // Set defaults
            $questionData['is_active'] = true;
            $questionData['is_public'] = true;
            $questionData['is_survey_question'] = false;
            $questionData['created_by'] = Auth::id();

            // Create the question
            $question = $this->storeQuestionAction->handle($questionData);

            // Create options for choice-based questions
            if ($this->questionNeedsOptions($questionData['type']) && ! empty($options)) {
                $this->createQuestionOptions($question, $options);
            }

            $questions[] = $question;
        }

        return $questions;
    }

    /** Check if question type needs options. */
    protected function questionNeedsOptions(string $type): bool
    {
        return in_array($type, [
            QuestionTypeEnum::SINGLE_CHOICE->value,
            QuestionTypeEnum::MULTIPLE_CHOICE->value,
        ]);
    }

    /**
     * Create options for a question.
     *
     * @param array<int, array{content: string, is_correct: bool, order: int}> $options
     */
    protected function createQuestionOptions(Question $question, array $options): void
    {
        foreach ($options as $optionData) {
            $question->options()->create([
                'content' => $optionData['content'],
                'type' => 'text',
                'is_correct' => $optionData['is_correct'],
                'order' => $optionData['order'],
                'metadata' => [],
            ]);
        }
    }

    /**
     * Create exam and attach questions.
     *
     * @param  array{
     *     title: string,
     *     description: string,
     *     type: string,
     *     total_score: int|float,
     *     duration: int,
     *     pass_score: int|float,
     *     max_attempts: int,
     *     shuffle_questions: bool,
     *     show_results: string,
     *     allow_review: bool,
     *     status: string,
     *     tags?: array<int, string>
     * }  $examData
     * @param array<int, Question> $questions
     */
    protected function createExamWithQuestions(array $examData, array $questions): void
    {
        // Create exam using StoreExamAction
        $exam = $this->storeExamAction->handle($examData);

        // Attach questions with weight of 1 each
        foreach ($questions as $index => $question) {
            $this->examService->attachQuestion(
                exam: $exam,
                question: $question,
                weight: 1,
                order: $index + 1
            );
        }
    }
}
