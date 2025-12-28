<?php

declare(strict_types=1);

namespace App\Services\Menu\Builders;

use App\Models\User;
use App\Services\Menu\Contracts\MenuBuilderInterface;
use App\Services\Menu\MenuPermissionChecker;

abstract class BaseMenuBuilder implements MenuBuilderInterface
{
    public function __construct(
        protected readonly MenuPermissionChecker $permissionChecker
    ) {}

    /**
     * Build menu array for the given user
     *
     * @return array<int, array<string, mixed>>
     */
    abstract public function build(User $user): array;

    /**
     * Check permission using permission checker
     *
     * @param array<int|string, mixed> $permissions
     */
    protected function checkPermission(array $permissions): bool
    {
        return $this->permissionChecker->checkPermission($permissions);
    }

    /** Check if user has access to module */
    protected function hasAccessToModule(string $moduleName): bool
    {
        return $this->permissionChecker->hasAccessToModule($moduleName) || config('custom-modules.show_future_modules', false);
    }
}
