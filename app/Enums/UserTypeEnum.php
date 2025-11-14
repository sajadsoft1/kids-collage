<?php

declare(strict_types=1);

namespace App\Enums;

enum UserTypeEnum: string
{
    use EnumToArray;
    case TEACHER = 'teacher';
    case EMPLOYEE = 'employee';
    case PARENT = 'parent';
    case USER = 'user';

    public static function options(): array
    {
        return [
            [
                'label' => self::TEACHER->title(),
                'value' => self::TEACHER->value,
            ],
            [
                'label' => self::EMPLOYEE->title(),
                'value' => self::EMPLOYEE->value,
            ],
            [
                'label' => self::PARENT->title(),
                'value' => self::PARENT->value,
            ],
            [
                'label' => self::USER->title(),
                'value' => self::USER->value,
            ],
        ];
    }

    public static function employeeOptions(): array
    {
        return [
            [
                'label' => self::EMPLOYEE->title(),
                'value' => self::EMPLOYEE->value,
            ],
            [
                'label' => self::TEACHER->title(),
                'value' => self::TEACHER->value,
            ],
        ];
    }

    public function title()
    {
        return match ($this) {
            self::TEACHER => trans('user.type_enums.teacher'),
            self::EMPLOYEE => trans('user.type_enums.employee'),
            self::PARENT => trans('user.type_enums.parent'),
            self::USER => trans('user.type_enums.user'),
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
