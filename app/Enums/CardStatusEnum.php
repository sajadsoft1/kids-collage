<?php

declare(strict_types=1);

namespace App\Enums;

enum CardStatusEnum: string
{
    use EnumToArray;

    case DRAFT     = 'draft';
    case ACTIVE    = 'active';
    case COMPLETED = 'completed';
    case ARCHIVED  = 'archived';

    public static function options(): array
    {
        return [
            [
                'label' => __('kanban.status.draft'),
                'value' => self::DRAFT->value,
            ],
            [
                'label' => __('kanban.status.active'),
                'value' => self::ACTIVE->value,
            ],
            [
                'label' => __('kanban.status.completed'),
                'value' => self::COMPLETED->value,
            ],
            [
                'label' => __('kanban.status.archived'),
                'value' => self::ARCHIVED->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::DRAFT => __('kanban.status.draft'),
            self::ACTIVE => __('kanban.status.active'),
            self::COMPLETED => __('kanban.status.completed'),
            self::ARCHIVED => __('kanban.status.archived'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
            'color' => match ($this) {
                self::DRAFT => 'neutral',
                self::ACTIVE => 'primary',
                self::COMPLETED => 'success',
                self::ARCHIVED => 'error',
            },
            'icon' => match ($this) {
                self::DRAFT => 'o-document',
                self::ACTIVE => 'o-play',
                self::COMPLETED => 'o-check-circle',
                self::ARCHIVED => 'o-archive-box',
            },
        ];
    }
}
