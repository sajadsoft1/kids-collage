<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Survey\StoreSurveyAction;
use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Enums\QuestionTypeEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SurveyAllowsNeutralQuestionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_survey_questions_can_be_saved_without_correct_answers(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $payload = [
            'title' => 'Feedback Survey',
            'description' => 'Tell us what you think.',
            'type' => ExamTypeEnum::SURVEY->value,
            'starts_at' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
            'status' => ExamStatusEnum::DRAFT->value,
            'rules' => [
                'group_logic' => 'or',
                'groups' => [
                    [
                        'logic' => 'and',
                        'conditions' => [
                            [
                                'field' => 'enrollment_date',
                                'operator' => 'after',
                                'value' => '2024-01-01',
                            ],
                        ],
                    ],
                ],
            ],
            'questions' => [
                [
                    'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
                    'title' => 'How satisfied are you with our program?',
                    'body' => null,
                    'options' => [
                        ['content' => 'Very satisfied', 'is_correct' => false, 'order' => 1],
                        ['content' => 'Satisfied', 'is_correct' => false, 'order' => 2],
                    ],
                    'config' => ['has_correct_answer' => false],
                    'correct_answer' => [],
                ],
                [
                    'type' => QuestionTypeEnum::MULTIPLE_CHOICE->value,
                    'title' => 'Which areas should we improve?',
                    'body' => null,
                    'options' => [
                        ['content' => 'Communication', 'is_correct' => false, 'order' => 1],
                        ['content' => 'Scheduling', 'is_correct' => false, 'order' => 2],
                        ['content' => 'Facilities', 'is_correct' => false, 'order' => 3],
                    ],
                    'config' => ['has_correct_answer' => false],
                    'correct_answer' => [],
                ],
            ],
        ];

        $exam = StoreSurveyAction::run($payload);

        $exam->load('questions.options');

        $this->assertDatabaseHas('exams', ['id' => $exam->id]);
        $this->assertCount(2, $exam->questions);

        $exam->questions->each(function ($question) {
            $this->assertTrue($question->is_survey_question);
            $this->assertFalse($question->config['has_correct_answer']);
            $this->assertEmpty($question->correct_answer ?? []);
            $this->assertTrue(
                $question->options->every(fn ($option) => $option->is_correct === false)
            );
        });
    }
}
