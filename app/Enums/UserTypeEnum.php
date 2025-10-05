<?php

declare(strict_types=1);

namespace App\Enums;

enum UserTypeEnum: string
{
    use EnumToArray;
    case TEACHER   = 'teacher';
    case EMPLOYEE  = 'employee';
    case PARENT    ='parent';
    case USER      = 'user';

    public static function options(): array
    {
        return [
            [
                'label' => trans('user.type_enums.teacher'),
                'value' => self::TEACHER->value,
            ],
            [
                'label' => trans('user.type_enums.employee'),
                'value' => self::EMPLOYEE->value,
            ],
            [
                'label' => trans('user.type_enums.parent'),
                'value' => self::PARENT->value,
            ],
            [
                'label' => trans('user.type_enums.user'),
                'value' => self::USER->value,
            ],
        ];
    }

    public static function employeeOptions(): array
    {
        return [
            [
                'label' => trans('user.type_enums.employee'),
                'value' => self::EMPLOYEE->value,
            ],
            [
                'label' => trans('user.type_enums.teacher'),
                'value' => self::TEACHER->value,
            ],
        ];
    }

    public function title()
    {
        return match ($this) {
            self::TEACHER  => trans('user.type.teacher'),
            self::EMPLOYEE => trans('user.type.employee'),
            self::PARENT   => trans('user.type.parent'),
            self::USER     => trans('user.type.user'),
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
