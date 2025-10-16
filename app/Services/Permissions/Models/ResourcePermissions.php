<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Resource;

class ResourcePermissions extends BasePermissions
{
    public const All     = 'Resource.All';
    public const Index   = 'Resource.Index';
    public const Show    = 'Resource.Show';
    public const Store   = 'Resource.Store';
    public const Update  = 'Resource.Update';
    public const Toggle  = 'Resource.Toggle';
    public const Delete  = 'Resource.Delete';
    public const Restore = 'Resource.Restore';

    protected string $model = Resource::class;
}
