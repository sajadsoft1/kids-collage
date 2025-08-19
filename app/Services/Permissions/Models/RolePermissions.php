<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Role;

class RolePermissions extends BasePermissions
{
    public const All     = 'Role.All';
    public const Index   = 'Role.Index';
    public const Show    = 'Role.Show';
    public const Store   = 'Role.Store';
    public const Update  = 'Role.Update';
    public const Toggle  = 'Role.Toggle';
    public const Delete  = 'Role.Delete';
    public const Restore = 'Role.Restore';

    protected string $model = Role::class;
}
