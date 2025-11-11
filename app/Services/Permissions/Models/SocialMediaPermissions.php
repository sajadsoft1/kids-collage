<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\SocialMedia;

class SocialMediaPermissions extends BasePermissions
{
    public const All = 'SocialMedia.All';
    public const Index = 'SocialMedia.Index';
    public const Show = 'SocialMedia.Show';
    public const Store = 'SocialMedia.Store';
    public const Update = 'SocialMedia.Update';
    public const Toggle = 'SocialMedia.Toggle';
    public const Delete = 'SocialMedia.Delete';
    public const Restore = 'SocialMedia.Restore';

    protected string $model = SocialMedia::class;
}
