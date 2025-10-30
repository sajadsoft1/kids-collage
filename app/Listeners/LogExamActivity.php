<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ExamCompleted;
use App\Events\ExamStarted;
use Illuminate\Support\Facades\Log;

class LogExamActivity
{
    public function handleStarted(ExamStarted $event): void
    {
        Log::info('Exam started', [
            'attempt_id' => $event->attempt->id,
            'exam_id'    => $event->attempt->exam_id,
            'user_id'    => $event->attempt->user_id,
        ]);
    }

    public function handleCompleted(ExamCompleted $event): void
    {
        Log::info('Exam completed', [
            'attempt_id' => $event->attempt->id,
            'score'      => $event->attempt->total_score,
            'duration'   => $event->attempt->getElapsedTime(),
        ]);
    }

    public function subscribe($events): array
    {
        return [
            ExamStarted::class   => 'handleStarted',
            ExamCompleted::class => 'handleCompleted',
        ];
    }
}
