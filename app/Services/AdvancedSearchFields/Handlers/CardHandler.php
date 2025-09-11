<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

use App\Enums\CardTypeEnum;
use App\Enums\PriorityEnum;
use App\Enums\CardStatusEnum;
use App\Models\Board;
use App\Models\User;

class CardHandler extends BaseHandler
{
    public function handle(): array
    {
        $boards = [];
        foreach (Board::where('is_active', true)->get() as $board) {
            $boards[] = $this->option((string) $board->id, $board->name);
        }

        $cardTypes = [];
        foreach (CardTypeEnum::cases() as $type) {
            $cardTypes[] = $this->option($type->value, $type->title());
        }

        $priorities = [];
        foreach (PriorityEnum::cases() as $priority) {
            $priorities[] = $this->option($priority->value, $priority->title());
        }

        $statuses = [];
        foreach (CardStatusEnum::cases() as $status) {
            $statuses[] = $this->option($status->value, $status->title());
        }

        $assignees = [];
        foreach (User::whereHas('assignedCards')->get() as $user) {
            $assignees[] = $this->option((string) $user->id, $user->full_name);
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('board_id', __('validation.attributes.board'), self::SELECT, $boards),
            $this->add('title', __('validation.attributes.title'), self::INPUT),
            $this->add('card_type', __('validation.attributes.card_type'), self::SELECT, $cardTypes),
            $this->add('priority', __('validation.attributes.priority'), self::SELECT, $priorities),
            $this->add('status', __('validation.attributes.status'), self::SELECT, $statuses),
            $this->add('due_date', __('validation.attributes.due_date'), self::DATE),
            $this->add('assignee_id', __('validation.attributes.assignee'), self::SELECT, $assignees),
            $this->add('overdue', __('validation.attributes.overdue'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('has_assignees', __('validation.attributes.has_assignees'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
