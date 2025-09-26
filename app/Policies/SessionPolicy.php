<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class SessionPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Session::class, 'Index'));
    }

    public function view(User $user, Session $session): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Session::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Session::class, 'Store'));
    }

    public function update(User $user, Session $session): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Session::class, 'Update'));
    }

    public function delete(User $user, Session $session): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Session::class, 'Delete'));
    }

    public function restore(User $user, Session $session): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Session::class, 'Restore'));
    }

    public function forceDelete(User $user, Session $session): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Session::class));
    }
}
