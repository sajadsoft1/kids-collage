<?php

declare(strict_types=1);

namespace App\Services\Menu;

use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Services\Menu\Builders\BaseMenuBuilder;
use App\Services\Menu\Builders\EmployeeMenuBuilder;
use App\Services\Menu\Builders\ParentMenuBuilder;
use App\Services\Menu\Builders\TeacherMenuBuilder;
use App\Services\Menu\Builders\UserMenuBuilder;
use App\Services\SmartCache;

readonly class MenuBuilderFactory
{
    public function __construct(
        private MenuPermissionChecker $permissionChecker
    ) {}

    /** Create menu builder for user type */
    public function createForUserType(UserTypeEnum $userType): BaseMenuBuilder
    {
        return match ($userType) {
            UserTypeEnum::EMPLOYEE => new EmployeeMenuBuilder($this->permissionChecker),
            UserTypeEnum::TEACHER => new TeacherMenuBuilder($this->permissionChecker),
            UserTypeEnum::PARENT => new ParentMenuBuilder($this->permissionChecker),
            UserTypeEnum::USER => new UserMenuBuilder($this->permissionChecker),
        };
    }

    /** Create menu builder for user */
    public function createForUser(User $user): BaseMenuBuilder
    {
        return SmartCache::for(User::class)
            ->key('menu_builder_factory_' . $user->id)
            ->remember(function () use ($user) {
                return $this->createForUserType($user->type);
            }, 3600);
    }
}
