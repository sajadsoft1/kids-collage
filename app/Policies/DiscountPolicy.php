<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Discount;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class DiscountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Discount::class, 'Index'));
    }

    public function view(User $user, Discount $discount): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Discount::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Discount::class, 'Store'));
    }

    public function update(User $user, Discount $discount): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Discount::class, 'Update'));
    }

    public function delete(User $user, Discount $discount): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Discount::class, 'Delete'));
    }

    public function restore(User $user, Discount $discount): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Discount::class, 'Restore'));
    }

    public function forceDelete(User $user, Discount $discount): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Discount::class));
    }
}
