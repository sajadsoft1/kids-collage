<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Card;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Card::class, 'Index'));
    }

    public function view(User $user, Card $card): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Card::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Card::class, 'Store'));
    }

    public function update(User $user, Card $card): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Card::class, 'Update'));
    }

    public function delete(User $user, Card $card): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Card::class, 'Delete'));
    }

    public function restore(User $user, Card $card): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Card::class, 'Restore'));
    }

    public function forceDelete(User $user, Card $card): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Card::class));
    }
}
