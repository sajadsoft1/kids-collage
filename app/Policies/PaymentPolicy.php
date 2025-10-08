<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Index'));
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Store'));
    }

    public function update(User $user, Payment $payment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Update'));
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Delete'));
    }

    public function restore(User $user, Payment $payment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Restore'));
    }

    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class));
    }
}
