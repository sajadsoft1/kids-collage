<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class RoomPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class, 'Index'));
    }

    public function view(User $user, Room $room): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class, 'Store'));
    }

    public function update(User $user, Room $room): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class, 'Update'));
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class, 'Delete'));
    }

    public function restore(User $user, Room $room): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class, 'Restore'));
    }

    public function forceDelete(User $user, Room $room): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class));
    }
}
