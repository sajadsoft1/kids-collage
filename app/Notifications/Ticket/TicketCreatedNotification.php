<?php

declare(strict_types=1);

namespace App\Notifications\Ticket;

use App\Enums\NotificationEventEnum;
use App\Models\Profile;
use App\Models\Ticket;
use App\Notifications\BaseNotification;

class TicketCreatedNotification extends BaseNotification
{
    /** Create a new notification instance. */
    public function __construct(
        private readonly Ticket $ticket,
        private readonly ?Profile $profile = null,
    ) {
        parent::__construct();
    }

    public function event(): NotificationEventEnum
    {
        return NotificationEventEnum::TICKET_CREATED;
    }

    /** @return array<string, mixed> */
    protected function context(object $notifiable): array
    {
        return [
            'user_name' => $notifiable->full_name ?? null,
            'ticket_number' => $this->ticket->key ?? $this->ticket->id,
            'ticket_subject' => $this->ticket->subject,
            'action_url' => route('admin.ticket.index', $this->ticket->id),
        ];
    }

    public function send(object $notifiable): void
    {
        $this->deliver($notifiable, $this->profile);
    }
}
