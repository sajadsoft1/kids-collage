<?php

declare(strict_types=1);

namespace App\Services\TicketChat;

use Karnoweb\TicketChat\Models\Conversation;

class TagService
{
    /** @param array<int> $tagIds */
    public function syncConversationTags(Conversation $conversation, array $tagIds): void
    {
        $conversation->tags()->sync($tagIds);
    }
}
