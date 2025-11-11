<?php

declare(strict_types=1);

namespace App\Enums;

use App\Services\Permissions\Models\AttendancePermissions;
use App\Services\Permissions\Models\CoursePermissions;
use App\Services\Permissions\Models\CourseTemplatePermissions;
use App\Services\Permissions\Models\DiscountPermissions;
use App\Services\Permissions\Models\SharedPermissions;

enum RoleEnum: string
{
    use EnumToArray;

    case DEVELOPER = 'developer';
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case PARENT = 'parent';
    case USER = 'user';

    public function title(): string
    {
        return match ($this) {
            self::DEVELOPER => 'Developer',
            self::ADMIN => 'Admin',
            self::TEACHER => trans('user.type_enums.teacher'),
            self::PARENT => trans('user.type_enums.parent'),
            self::USER => trans('user.type_enums.user'),
        };
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'title' => $this->title(),
        ];
    }

    public function permissions(): array
    {
        return match ($this) {
            self::DEVELOPER => [SharedPermissions::Admin],
            self::ADMIN => [SharedPermissions::Admin, SharedPermissions::ReceiveNewUserSms],
            self::TEACHER => [
                AttendancePermissions::Index,
                AttendancePermissions::Show,
                AttendancePermissions::Store,
                CoursePermissions::Index,
                CoursePermissions::Show,
                CourseTemplatePermissions::Index,
                CourseTemplatePermissions::Show,
                DiscountPermissions::Index,
                DiscountPermissions::Show,
            ],
            self::PARENT => [
                AttendancePermissions::Index,
                AttendancePermissions::Show,
                AttendancePermissions::Store,
                CoursePermissions::Index,
                CoursePermissions::Show,
                CourseTemplatePermissions::Index,
                CourseTemplatePermissions::Show,
                DiscountPermissions::Index,
                DiscountPermissions::Show],
            self::USER => [
                AttendancePermissions::Index,
                AttendancePermissions::Show,
                AttendancePermissions::Store,
                CoursePermissions::Index,
                CoursePermissions::Show,
                CourseTemplatePermissions::Index,
                CourseTemplatePermissions::Show,
                DiscountPermissions::Index,
                DiscountPermissions::Show,
            ],
        };
    }
}
