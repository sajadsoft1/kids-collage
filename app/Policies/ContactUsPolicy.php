<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ContactUs;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ContactUsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Index'));
    }

    public function view(User $user, ContactUs $contactUs): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Store'));
    }

    public function update(User $user, ContactUs $contactUs): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Update'));
    }

    public function delete(User $user, ContactUs $contactUs): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Delete'));
    }

    public function restore(User $user, ContactUs $contactUs): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Restore'));
    }

    public function forceDelete(User $user, ContactUs $contactUs): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class));
    }
}
