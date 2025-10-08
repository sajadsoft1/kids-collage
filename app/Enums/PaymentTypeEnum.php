<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentTypeEnum: string
{
    use EnumToArray;

    case ONLINE       = 'online';
    case CASH         = 'cash';
    case CARD_TO_CARD = 'card_to_card';

    public static function options(): array
    {
        return [
            [
                'value' => self::ONLINE->value,
                'label' => self::ONLINE->title(),
            ],
            [
                'value' => self::CASH->value,
                'label' => self::CASH->title(),
            ],
            [
                'value' => self::CARD_TO_CARD->value,
                'label' => self::CARD_TO_CARD->title(),
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::ONLINE       => 'ONLINE',
            self::CASH         => 'CASH',
            self::CARD_TO_CARD => 'CARD_TO_CARD',
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
        ];
    }
}
