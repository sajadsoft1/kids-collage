<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseTypeEnum: string
{
    use EnumToArray;

    case INPERSON  = 'in-person';
    case ONLINE = 'online';
    case HYBRID  = 'hybrid';

    public function title(): string
    {
        return match ($this) {
            self::INPERSON  => 'INPERSON',
            self::ONLINE => 'ONLINE',
            self::HYBRID  => 'HYBRID',
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
