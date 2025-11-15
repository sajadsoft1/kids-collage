<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class EventPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Event::class, 'Index'));
    }

    public function view(User $user, Event $event): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Event::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Event::class, 'Store'));
    }

    public function update(User $user, Event $event): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Event::class, 'Update'));
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Event::class, 'Delete'));
    }

    public function restore(User $user, Event $event): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Event::class, 'Restore'));
    }

    public function forceDelete(User $user, Event $event): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Event::class));
    }
}
