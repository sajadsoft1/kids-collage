<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Index'));
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Store'));
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Update'));
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Delete'));
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Restore'));
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class));
    }
}
