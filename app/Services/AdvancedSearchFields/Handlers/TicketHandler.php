<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

use App\Enums\TicketDepartmentEnum;
use App\Enums\TicketPriorityEnum;
use App\Enums\TicketStatusEnum;
use App\Models\User;

class TicketHandler extends BaseHandler
{
    public function handle(): array
    {
        $departments = [];
        foreach (TicketDepartmentEnum::cases() as $department) {
            $departments[] = $this->option($department->value, $department->title());
        }

        $priorities = [];
        foreach (TicketPriorityEnum::cases() as $priority) {
            $priorities[] = $this->option((string) $priority->value, $priority->title());
        }

        $statuses = [];
        foreach (TicketStatusEnum::cases() as $status) {
            $statuses[] = $this->option($status->value, $status->title());
        }

        $users = [];
        foreach (User::whereHas('tickets')->get() as $user) {
            $users[] = $this->option((string) $user->id, $user->full_name);
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('subject', __('validation.attributes.subject'), self::INPUT),
            $this->add('department', __('validation.attributes.department'), self::SELECT, $departments),
            $this->add('status', __('validation.attributes.status'), self::SELECT, $statuses),
            $this->add('priority', __('validation.attributes.priority'), self::SELECT, $priorities),
            $this->add('user_id', __('validation.attributes.user'), self::SELECT, $users),
            $this->add('key', __('validation.attributes.ticket_key'), self::INPUT),
            $this->add('has_messages', __('validation.attributes.has_messages'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('message_count', __('validation.attributes.message_count'), self::NUMBER),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
