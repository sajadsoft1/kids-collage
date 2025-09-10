<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TicketMessage;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class TicketMessagePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(TicketMessage::class, 'Index'));
    }

    public function view(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(TicketMessage::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(TicketMessage::class, 'Store'));
    }

    public function update(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(TicketMessage::class, 'Update'));
    }

    public function delete(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(TicketMessage::class, 'Delete'));
    }

    public function restore(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(TicketMessage::class, 'Restore'));
    }

    public function forceDelete(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(TicketMessage::class));
    }
}
