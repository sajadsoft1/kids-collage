<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\QuestionTypeEnum;

return [
    'question_competency' => [
        [
            'title' => 'کوپتانسی مهارتی',
            'description' => 'توضیحات کوپتانسی مهارتی',
        ],
    ],
    'question_subject' => [
        [
            'title' => 'مهارتی',
            'description' => 'توضیحات مهارتی',
            'ordering' => 1,
            'published' => BooleanEnum::ENABLE->value,
        ],
    ],
    'question' => [
        [
            'question' => 'سوال مهارتی',
            'subject_id' => 1,
            'competency_id' => 1,
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'options' => [
                [
                    'option' => 'گزینه A',
                    'is_correct' => true,
                ],
                [
                    'option' => 'گزینه B',
                    'is_correct' => false,
                ],
                [
                    'option' => 'گزینه C',
                    'is_correct' => false,
                ],
                [
                    'option' => 'گزینه D',
                    'is_correct' => false,
                ],
            ],
        ],
    ],
];
