<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Teammate;

class TeammatePermissions extends BasePermissions
{
    public const All = 'Teammate.All';
    public const Index = 'Teammate.Index';
    public const Show = 'Teammate.Show';
    public const Store = 'Teammate.Store';
    public const Update = 'Teammate.Update';
    public const Toggle = 'Teammate.Toggle';
    public const Delete = 'Teammate.Delete';
    public const Restore = 'Teammate.Restore';

    protected string $model = Teammate::class;
}
