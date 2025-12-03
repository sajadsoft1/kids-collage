<?php

declare(strict_types=1);

namespace App\Enums;

enum TaxonomyTypeEnum: string
{
    use EnumToArray;

    case FLASHCARD = 'flashcard';
    case NOTEBOOK = 'notebook';

    public static function options(): array
    {
        return [
            [
                'label' => __('core.flashcard'),
                'value' => self::FLASHCARD->value,
            ],
            [
                'label' => __('core.notebook'),
                'value' => self::NOTEBOOK->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::FLASHCARD => __('core.flashcard'),
            self::NOTEBOOK => __('core.notebook'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => (bool) $this->value,
            'label' => $this->title(),
        ];
    }
}
