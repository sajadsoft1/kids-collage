<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Faq;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class FaqPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Index'));
    }

    public function view(User $user, Faq $faq): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Store'));
    }

    public function update(User $user, Faq $faq): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Update'));
    }

    public function delete(User $user, Faq $faq): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Delete'));
    }

    public function restore(User $user, Faq $faq): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Restore'));
    }

    public function forceDelete(User $user, Faq $faq): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class));
    }
}
