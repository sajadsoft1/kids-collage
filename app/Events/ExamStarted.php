<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ExamAttempt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ExamAttempt $attempt
    ) {}

    /** کانال‌های Broadcasting */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('exam.' . $this->attempt->exam_id),
            new Channel('user.' . $this->attempt->user_id),
        ];
    }

    /** داده‌های Broadcasting */
    public function broadcastWith(): array
    {
        return [
            'attempt_id' => $this->attempt->id,
            'exam_id'    => $this->attempt->exam_id,
            'user_id'    => $this->attempt->user_id,
            'started_at' => $this->attempt->started_at,
        ];
    }
}
