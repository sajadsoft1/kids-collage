<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Opinion;

class OpinionPermissions extends BasePermissions
{
    public const All = 'Opinion.All';
    public const Index = 'Opinion.Index';
    public const Show = 'Opinion.Show';
    public const Store = 'Opinion.Store';
    public const Update = 'Opinion.Update';
    public const Toggle = 'Opinion.Toggle';
    public const Delete = 'Opinion.Delete';
    public const Restore = 'Opinion.Restore';

    protected string $model = Opinion::class;
}
