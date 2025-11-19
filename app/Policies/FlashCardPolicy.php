<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FlashCard;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class FlashCardPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Index'));
    }

    public function view(User $user, FlashCard $flashCard): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Store'));
    }

    public function update(User $user, FlashCard $flashCard): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Update'));
    }

    public function delete(User $user, FlashCard $flashCard): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Delete'));
    }

    public function restore(User $user, FlashCard $flashCard): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Restore'));
    }

    public function forceDelete(User $user, FlashCard $flashCard): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class));
    }
}
