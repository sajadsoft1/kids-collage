<?php

declare(strict_types=1);

namespace App\Enums;

enum UserTypeEnum: string
{
    use EnumToArray;
    case TEACHER   = 'teacher';
    case EMPLOYEE  = 'employee';
    case PARENT  ='parent';
    case USER  = 'user';


    public function title()
    {
        return match ($this) {
            self::TEACHER   => trans('user.teacher'),
            self::EMPLOYEE   => trans('user.employee'),
            self::PARENT   => trans('user.parent'),
            self::USER   => trans('user.user'),

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
