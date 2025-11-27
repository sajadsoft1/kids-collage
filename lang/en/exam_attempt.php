<?php

declare(strict_types=1);

return [
    'model' => 'Exam Attempt',
    'index_title' => 'Exam Attempts',

    // Columns
    'exam' => 'Exam',
    'user' => 'User',
    'status' => 'Status',
    'total_score' => 'Total Score',
    'percentage' => 'Percentage',
    'answers_count' => 'Answers',
    'started_at' => 'Started At',
    'completed_at' => 'Completed At',
    'duration' => 'Duration',

    // Actions
    'continue' => 'Continue Exam',
    'view_results' => 'View Results',

    // Messages
    'no_attempts' => 'No exam attempts found.',

    // Status
    'in_progress' => 'In Progress',
    'completed' => 'Completed',
    'abandoned' => 'Abandoned',
    'expired' => 'Expired',

    // Results page
    'back_to_list' => 'Back to Attempts',
];
