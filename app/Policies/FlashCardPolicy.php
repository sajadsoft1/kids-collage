<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserTypeEnum;
use App\Models\FlashCard;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class FlashCardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Index')) ||
            in_array($user->type, [UserTypeEnum::USER, UserTypeEnum::PARENT]);
    }

    public function view(User $user, FlashCard $flashCard): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Show')) ||
            in_array($user->type, [UserTypeEnum::USER, UserTypeEnum::PARENT]);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Store')) ||
            $user->type == UserTypeEnum::USER;
    }

    public function update(User $user, FlashCard $flashCard): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Update')) ||
            $user->id == $flashCard->user_id;
    }

    public function delete(User $user, FlashCard $flashCard): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(FlashCard::class, 'Delete')) ||
            $user->id == $flashCard->user_id;
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
