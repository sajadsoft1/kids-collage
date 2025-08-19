<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Slider;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class SliderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Index'));
    }

    public function view(User $user, Slider $slider): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Store'));
    }

    public function update(User $user, Slider $slider): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Update'));
    }

    public function delete(User $user, Slider $slider): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Delete'));
    }

    public function restore(User $user, Slider $slider): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Restore'));
    }

    public function forceDelete(User $user, Slider $slider): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class));
    }
}
