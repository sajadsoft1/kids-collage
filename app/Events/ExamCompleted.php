<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ExamAttempt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ExamAttempt $attempt
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('exam.' . $this->attempt->exam_id),
            new Channel('user.' . $this->attempt->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'attempt_id' => $this->attempt->id,
            'total_score' => $this->attempt->total_score,
            'percentage' => $this->attempt->percentage,
            'completed_at' => $this->attempt->completed_at,
        ];
    }
}
