<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Sms;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class SmsPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Sms::class, 'Index'));
    }

    public function view(User $user, Sms $sms): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Sms::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Sms::class, 'Store'));
    }

    public function update(User $user, Sms $sms): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Sms::class, 'Update'));
    }

    public function delete(User $user, Sms $sms): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Sms::class, 'Delete'));
    }

    public function restore(User $user, Sms $sms): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Sms::class, 'Restore'));
    }

    public function forceDelete(User $user, Sms $sms): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Sms::class));
    }
}
