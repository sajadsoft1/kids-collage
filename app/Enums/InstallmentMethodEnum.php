<?php

declare(strict_types=1);

namespace App\Enums;

enum InstallmentMethodEnum: string
{
    use EnumToArray;

    case ONLINE = 'online';
    case OFFLINE = 'offline';

    public function title(): string
    {
        return match ($this) {
            self::ONLINE => 'ONLINE',
            self::OFFLINE => 'OFFLINE',
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
