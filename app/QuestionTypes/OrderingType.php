<?php

declare(strict_types=1);

namespace App\QuestionTypes;

class OrderingType extends AbstractQuestionType
{
    public function defaultConfig(): array
    {
        return [
            'min_items' => 2,
            'max_items' => 10,
            'scoring_type' => 'exact', // exact, partial, adjacent
        ];
    }

    public function validationRules(): array
    {
        $config = $this->getConfig();

        return [
            'title' => ['required', 'string', 'max:2000'],
            'body' => ['nullable', 'string'],
            'default_score' => ['required', 'numeric', 'min:0'],

            'options' => [
                'required',
                'array',
                "min:{$config['min_items']}",
                "max:{$config['max_items']}",
            ],
            'options.*.content' => ['required', 'string'],
            'options.*.order' => ['required', 'integer', 'distinct'],

            'config.scoring_type' => ['nullable', 'in:exact,partial,adjacent'],
        ];
    }

    public function validateAnswer(mixed $answer): bool
    {
        if ( ! is_array($answer) || empty($answer)) {
            return false;
        }

        $optionIds = $this->getOptions()->pluck('id')->toArray();

        // بررسی تعداد و یکتایی
        return count($answer) === count($optionIds)
            && count($answer) === count(array_unique($answer))
            && empty(array_diff($answer, $optionIds));
    }

    public function calculateScore(mixed $answer): float
    {
        $correctOrder = $this->getOptions()
            ->sortBy('order')
            ->pluck('id')
            ->toArray();

        $config = $this->getConfig();
        $weight = $this->getWeight();

        switch ($config['scoring_type']) {
            case 'exact':
                // همه یا هیچ
                return $answer === $correctOrder ? $weight : 0;
            case 'adjacent':
                // نمره بر اساس جفت‌های مجاور صحیح
                $correctPairs = 0;
                $totalPairs = count($correctOrder) - 1;

                for ($i = 0; $i < $totalPairs; $i++) {
                    $currentIndex = array_search($correctOrder[$i], $answer);
                    $nextIndex = array_search($correctOrder[$i + 1], $answer);

                    if ($currentIndex !== false && $nextIndex !== false && $nextIndex === $currentIndex + 1) {
                        $correctPairs++;
                    }
                }

                return round(($correctPairs / $totalPairs) * $weight, 2);
            case 'partial':
            default:
                // نمره بر اساس موقعیت‌های صحیح
                $correctPositions = 0;
                foreach ($answer as $index => $optionId) {
                    if ($correctOrder[$index] === $optionId) {
                        $correctPositions++;
                    }
                }

                return round(($correctPositions / count($correctOrder)) * $weight, 2);
        }
    }

    public function builderComponent(): string
    {
        return 'question-builder.ordering';
    }

    public function displayComponent(): string
    {
        return 'question-display.ordering';
    }

    public function getCorrectAnswer(): mixed
    {
        return $this->getOptions()
            ->sortBy('order')
            ->pluck('id')
            ->toArray();
    }

    public function supportsPartialCredit(): bool
    {
        return in_array($this->getConfig()['scoring_type'], ['partial', 'adjacent']);
    }
}
