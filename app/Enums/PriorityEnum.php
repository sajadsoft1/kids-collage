<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Priority Enum
 *
 * Defines priority levels for tasks and content.
 *
 * @OA\Schema(
 *     schema="PriorityEnum",
 *     @OA\Property(property="value", type="string", enum={"low", "medium", "high", "urgent"}),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="color", type="string"),
 *     @OA\Property(property="icon", type="string"),
 * ),
 */
enum PriorityEnum: string
{
    use EnumToArray;

    case LOW    = 'low';
    case MEDIUM = 'medium';
    case HIGH   = 'high';
    case URGENT = 'urgent';

    public static function options(): array
    {
        return [
            [
                'label' => __('kanban.priority.low'),
                'value' => self::LOW->value,
            ],
            [
                'label' => __('kanban.priority.medium'),
                'value' => self::MEDIUM->value,
            ],
            [
                'label' => __('kanban.priority.high'),
                'value' => self::HIGH->value,
            ],
            [
                'label' => __('kanban.priority.urgent'),
                'value' => self::URGENT->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::LOW    => __('kanban.priority.low'),
            self::MEDIUM => __('kanban.priority.medium'),
            self::HIGH   => __('kanban.priority.high'),
            self::URGENT => __('kanban.priority.urgent'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
            'color' => match ($this) {
                self::LOW    => 'success',
                self::MEDIUM => 'info',
                self::HIGH   => 'warning',
                self::URGENT => 'error',
            },
            'icon'  => match ($this) {
                self::LOW    => 'o-arrow-down',
                self::MEDIUM => 'o-minus',
                self::HIGH   => 'o-arrow-up',
                self::URGENT => 'o-exclamation-triangle',
            },
        ];
    }
}
