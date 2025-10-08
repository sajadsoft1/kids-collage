<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Installment;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class InstallmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Installment::class, 'Index'));
    }

    public function view(User $user, Installment $installment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Installment::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Installment::class, 'Store'));
    }

    public function update(User $user, Installment $installment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Installment::class, 'Update'));
    }

    public function delete(User $user, Installment $installment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Installment::class, 'Delete'));
    }

    public function restore(User $user, Installment $installment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Installment::class, 'Restore'));
    }

    public function forceDelete(User $user, Installment $installment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Installment::class));
    }
}
