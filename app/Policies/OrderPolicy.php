<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class OrderPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Index'));
    }

    public function view(User $user, Order $order): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Store'));
    }

    public function update(User $user, Order $order): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Update'));
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Delete'));
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Restore'));
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class));
    }
}
