<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Banner;

class BannerPermissions extends BasePermissions
{
    public const All = 'Banner.All';
    public const Index = 'Banner.Index';
    public const Show = 'Banner.Show';
    public const Store = 'Banner.Store';
    public const Update = 'Banner.Update';
    public const Toggle = 'Banner.Toggle';
    public const Delete = 'Banner.Delete';
    public const Restore = 'Banner.Restore';

    protected string $model = Banner::class;
}
