<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\User;

class UserPermissions extends BasePermissions
{
    public const All     = 'User.All';
    public const Index   = 'User.Index';
    public const Show    = 'User.Show';
    public const Store   = 'User.Store';
    public const Update  = 'User.Update';
    public const Toggle  = 'User.Toggle';
    public const Delete  = 'User.Delete';
    public const Restore = 'User.Restore';

    protected string $model = User::class;
}
