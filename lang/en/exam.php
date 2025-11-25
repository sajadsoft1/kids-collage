<?php

declare(strict_types=1);

return [
    'model' => 'Exam',
    'type' => 'Type',
    'status' => 'Status',
    'total_score' => 'Total Score',
    'duration' => 'Duration',
    'questions_count' => 'Questions',
    'manual_review_count' => 'Manual Review',
    'total_weight' => 'Total Weight',
    'attempts_count' => 'Attempts',
    'duration_label' => 'Duration (minutes)',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'validation' => [
        'total_weight_mismatch' => 'The total weight of questions does not match the total score. Please adjust the question weights.',
    ],
    'enum' => [
    ],
    'notifications' => [
    ],
    'form' => [
        'basic_tab' => 'Basic information',
        'rules_tab' => 'Eligibility rules',
        'questions_tab' => 'Questions',
        'basic_information' => 'General information',
        'scoring_settings' => 'Scoring & attempts',
        'schedule_settings' => 'Schedule & status',
        'delivery_settings' => 'Delivery & review options',
        'question_overview' => 'Question overview',
        'rules_builder_title' => 'Participation rules',
        'no_questions_title' => 'No questions attached yet',
        'no_questions_desc' => 'Attach questions from the question bank to view exam statistics.',
        'questions_manage_title' => 'Manage exam questions',
        'questions_need_save_title' => 'Save the exam to add questions',
        'questions_need_save_desc' => 'Please save the exam first, then you can pick questions from the bank.',
    ],
    'page' => [
        'builder' => [
            'group' => [
                'rules' => [
                    'and' => 'AND',
                    'or' => 'OR',
                ],
            ],
            'remove_group' => 'Remove group',
        ],
    ],
];
