<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Tag;

class TagPermissions extends BasePermissions
{
    public const All = 'Tag.All';
    public const Index = 'Tag.Index';
    public const Show = 'Tag.Show';
    public const Store = 'Tag.Store';
    public const Update = 'Tag.Update';
    public const Toggle = 'Tag.Toggle';
    public const Delete = 'Tag.Delete';
    public const Restore = 'Tag.Restore';

    protected string $model = Tag::class;
}
