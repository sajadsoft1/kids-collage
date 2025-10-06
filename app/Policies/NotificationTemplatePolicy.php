<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\NotificationTemplate;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class NotificationTemplatePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(NotificationTemplate::class, 'Index'));
    }

    public function view(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(NotificationTemplate::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(NotificationTemplate::class, 'Store'));
    }

    public function update(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(NotificationTemplate::class, 'Update'));
    }

    public function delete(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(NotificationTemplate::class, 'Delete'));
    }

    public function restore(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(NotificationTemplate::class, 'Restore'));
    }

    public function forceDelete(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(NotificationTemplate::class));
    }
}
