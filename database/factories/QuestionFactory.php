<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CategoryTypeEnum;
use App\Enums\DifficultyEnum;
use App\Enums\QuestionTypeEnum;
use App\Models\Category;
use App\Models\Question;
use App\Models\QuestionCompetency;
use App\Models\QuestionSubject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        $type = fake()->randomElement(QuestionTypeEnum::cases());

        return [
            'type'           => $type,
            'category_id'    => Category::where('type', CategoryTypeEnum::QUESTION->value)->first()->id,
            'subject_id'     => QuestionSubject::factory()->create([
                'category_id' => Category::where('type', CategoryTypeEnum::QUESTION->value)->first()->id,
            ]),
            'competency_id'  => QuestionCompetency::factory(),
            'title'          => fake()->sentence() . '?',
            'body'           => fake()->optional()->paragraph(),
            'explanation'    => fake()->optional()->paragraph(),
            'difficulty'     => fake()->randomElement(DifficultyEnum::cases()),
            'default_score'  => fake()->randomFloat(2, 1, 10),
            'config'         => $this->getConfigForType($type),
            'correct_answer' => $this->getCorrectAnswerForType($type),
            'metadata'       => [],
            'created_by'     => User::factory(),
            'is_active'      => true,
            'is_public'      => fake()->boolean(30),
            'is_survey_question' => false
        ];
    }

    protected function getConfigForType(QuestionTypeEnum $type): array
    {
        return match ($type) {
            QuestionTypeEnum::SINGLE_CHOICE,
            QuestionTypeEnum::MULTIPLE_CHOICE => [
                'shuffle_options' => fake()->boolean(),
            ],
            QuestionTypeEnum::ORDERING        => [
                'scoring_type' => fake()->randomElement(['exact', 'partial', 'adjacent']),
            ],
            default                           => [],
        };
    }

    protected function getCorrectAnswerForType(QuestionTypeEnum $type): ?array
    {
        return match ($type) {
            QuestionTypeEnum::TRUE_FALSE   => [
                'value' => fake()->boolean(),
            ],
            QuestionTypeEnum::SHORT_ANSWER => [
                'acceptable_answers' => [fake()->word()],
            ],
            default                        => null,
        };
    }

    public function singleChoice(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => QuestionTypeEnum::SINGLE_CHOICE,
        ]);
    }

    public function multipleChoice(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => QuestionTypeEnum::MULTIPLE_CHOICE,
        ]);
    }

    public function trueFalse(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'           => QuestionTypeEnum::TRUE_FALSE,
            'correct_answer' => ['value' => fake()->boolean()],
        ]);
    }
}
