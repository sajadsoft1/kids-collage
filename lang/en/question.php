<?php

declare(strict_types=1);

return [
    'builder' => [
        'common'     => [
            'settings'        => 'Settings',
            'shuffle_options' => 'Shuffle options on display',
        ],
        'multiple'   => [
            'add_option'             => 'Add option',
            'shuffle_options'        => 'Shuffle options on display',
            'scoring_type'           => 'Scoring type',
            'scoring_all_or_nothing' => 'All or nothing (all correct answers must be selected)',
            'scoring_partial'        => 'Partial (based on number of correct answers)',
        ],
        'single'     => [
            'add_option'       => 'Add option',
            'show_explanation' => 'Show explanation after answering',
        ],
        'true_false' => [
            'true_label'     => 'True label',
            'false_label'    => 'False label',
            'correct_answer' => 'Correct answer',
        ],
    ],
    'display' => [
        'multiple'               => [
            'hint_multi_select' => 'You can select multiple options',
        ],
        'correct'                => 'Correct answer',
        'should_not_be_selected' => 'Should not be selected',
        'explanation'            => 'Explanation',
    ],
    'info'    => [
        'select_one_correct' => 'Select at least one option as a correct answer.',
    ],
];
