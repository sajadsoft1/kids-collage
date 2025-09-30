<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Term;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class TermPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Term::class, 'Index'));
    }

    public function view(User $user, Term $term): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Term::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Term::class, 'Store'));
    }

    public function update(User $user, Term $term): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Term::class, 'Update'));
    }

    public function delete(User $user, Term $term): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Term::class, 'Delete'));
    }

    public function restore(User $user, Term $term): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Term::class, 'Restore'));
    }

    public function forceDelete(User $user, Term $term): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Term::class));
    }
}
