<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Index'));
    }

    public function view(User $user, Client $client): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Store'));
    }

    public function update(User $user, Client $client): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Update'));
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Delete'));
    }

    public function restore(User $user, Client $client): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Restore'));
    }

    public function forceDelete(User $user, Client $client): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class));
    }
}
