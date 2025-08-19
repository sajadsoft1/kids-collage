<?php

declare(strict_types=1);

namespace App\Enums;

enum CardTypeEnum: string
{
    use EnumToArray;

    case NOTE    = 'note';
    case TASK    = 'task';
    case BUG     = 'bug';
    case FEATURE = 'feature';
    case CALL    = 'call';
    case MEETING = 'meeting';
    case EMAIL   = 'email';
    case OTHER   = 'other';

    public static function options(): array
    {
        return [
            [
                'label' => __('kanban.note'),
                'value' => self::NOTE->value,
            ],
            [
                'label' => __('kanban.task'),
                'value' => self::TASK->value,
            ],
            [
                'label' => __('kanban.bug'),
                'value' => self::BUG->value,
            ],
            [
                'label' => __('kanban.feature'),
                'value' => self::FEATURE->value,
            ],
            [
                'label' => __('kanban.call'),
                'value' => self::CALL->value,
            ],
            [
                'label' => __('kanban.meeting'),
                'value' => self::MEETING->value,
            ],
            [
                'label' => __('kanban.email'),
                'value' => self::EMAIL->value,
            ],
            [
                'label' => __('kanban.other'),
                'value' => self::OTHER->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::NOTE    => __('kanban.note'),
            self::TASK    => __('kanban.task'),
            self::BUG     => __('kanban.bug'),
            self::FEATURE => __('kanban.feature'),
            self::CALL    => __('kanban.call'),
            self::MEETING => __('kanban.meeting'),
            self::EMAIL   => __('kanban.email'),
            self::OTHER   => __('kanban.other'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
            'color' => match ($this) {
                self::NOTE    => 'primary',
                self::TASK    => 'primary',
                self::BUG     => 'error',
                self::FEATURE => 'success',
                self::CALL    => 'info',
                self::MEETING => 'warning',
                self::EMAIL   => 'secondary',
                self::OTHER   => 'neutral',
            },
            'icon'  => match ($this) {
                self::NOTE    => 'o-clipboard-document-list',
                self::TASK    => 'o-clipboard-document-list',
                self::BUG     => 'o-exclamation-triangle',
                self::FEATURE => 'o-star',
                self::CALL    => 'o-phone',
                self::MEETING => 'o-users',
                self::EMAIL   => 'o-envelope',
                self::OTHER   => 'o-document',
            },
        ];
    }
}
