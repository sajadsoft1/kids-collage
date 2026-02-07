<?php

declare(strict_types=1);

namespace App\Services\TicketChat;

use Karnoweb\TicketChat\Models\Conversation;
use Karnoweb\TicketChat\Models\Feedback;

class FeedbackService
{
    /** Submit or update CSAT feedback for a conversation. */
    public function submit(
        Conversation $conversation,
        int $userId,
        int $rating,
        ?string $comment = null
    ): Feedback {
        return Feedback::query()->updateOrCreate(
            [
                'conversation_id' => $conversation->getKey(),
                'user_id' => $userId,
            ],
            [
                'rating' => $rating,
                'comment' => $comment,
            ]
        );
    }

    /** Get the current user's feedback for the given conversation (for CSAT display). */
    public function getForConversation(Conversation $conversation): ?Feedback
    {
        $userId = (int) auth()->id();

        return Feedback::query()
            ->where('conversation_id', $conversation->getKey())
            ->where('user_id', $userId)
            ->first();
    }
}
