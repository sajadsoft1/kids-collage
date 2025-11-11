<?php

declare(strict_types=1);

namespace App\QuestionTypes;

class TextHighlightType extends AbstractQuestionType
{
    public function defaultConfig(): array
    {
        return [
            'allow_multiple' => true,
            'scoring_type' => 'partial', // exact, partial
        ];
    }

    public function validationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:2000'],
            'body' => ['required', 'string'], // متن اصلی
            'correct_answer' => ['required', 'array'],
            'correct_answer.selections' => ['required', 'array', 'min:1'],
            'correct_answer.selections.*.start' => ['required', 'integer', 'min:0'],
            'correct_answer.selections.*.end' => ['required', 'integer'],
        ];
    }

    public function validateAnswer(mixed $answer): bool
    {
        if ( ! is_array($answer) || empty($answer['selections'] ?? [])) {
            return false;
        }

        foreach ($answer['selections'] as $selection) {
            if ( ! isset($selection['start']) || ! isset($selection['end'])) {
                return false;
            }
            if ($selection['start'] >= $selection['end']) {
                return false;
            }
        }

        return true;
    }

    public function calculateScore(mixed $answer): float
    {
        $correctSelections = $this->question->correct_answer['selections'] ?? [];
        $userSelections    = $answer['selections'] ?? [];

        $config = $this->getConfig();
        $weight = $this->getWeight();

        if ($config['scoring_type'] === 'exact') {
            // باید دقیقا همان بخش‌ها انتخاب شده باشند
            if (count($correctSelections) !== count($userSelections)) {
                return 0;
            }

            foreach ($correctSelections as $correct) {
                $found = false;
                foreach ($userSelections as $user) {
                    if ($user['start'] === $correct['start'] && $user['end'] === $correct['end']) {
                        $found = true;

                        break;
                    }
                }
                if ( ! $found) {
                    return 0;
                }
            }

            return $weight;
        }

        // نمره‌دهی جزئی
        $correctCount = 0;
        foreach ($correctSelections as $correct) {
            foreach ($userSelections as $user) {
                if ($this->overlaps($correct, $user)) {
                    $correctCount++;

                    break;
                }
            }
        }

        return round(($correctCount / count($correctSelections)) * $weight, 2);
    }

    protected function overlaps(array $range1, array $range2): bool
    {
        return $range1['start'] < $range2['end'] && $range2['start'] < $range1['end'];
    }

    public function builderComponent(): string
    {
        return 'question-builder.text-highlight';
    }

    public function displayComponent(): string
    {
        return 'question-display.text-highlight';
    }

    public function getCorrectAnswer(): mixed
    {
        return $this->question->correct_answer['selections'] ?? [];
    }

    public function supportsPartialCredit(): bool
    {
        return $this->getConfig()['scoring_type'] === 'partial';
    }
}
