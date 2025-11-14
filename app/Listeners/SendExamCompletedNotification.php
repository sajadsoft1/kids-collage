<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ExamCompleted;
use App\Notifications\ExamCompletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendExamCompletedNotification implements ShouldQueue
{
    public function handle(ExamCompleted $event): void
    {
        $attempt = $event->attempt;

        $attempt->user->notify(
            new ExamCompletedNotification($attempt)
        );
    }
}
