<?php

declare(strict_types=1);

namespace App\Enums;

enum GenderEnum: string
{
    use EnumToArray;
    case MALE = 'male';
    case FEMALE = 'female';

    public function title()
    {
        return match ($this) {
            self::MALE => trans('user.gender.male'),
            self::FEMALE => trans('user.gender.female')
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
