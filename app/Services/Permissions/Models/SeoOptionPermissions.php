<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\SeoOption;

class SeoOptionPermissions extends BasePermissions
{
    public const All = 'SeoOption.All';
    public const Index = 'SeoOption.Index';
    public const Show = 'SeoOption.Show';
    public const Store = 'SeoOption.Store';
    public const Update = 'SeoOption.Update';
    public const Toggle = 'SeoOption.Toggle';
    public const Delete = 'SeoOption.Delete';
    public const Restore = 'SeoOption.Restore';

    protected string $model = SeoOption::class;
}
