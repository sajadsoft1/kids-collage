<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Blog;

class BlogPermissions extends BasePermissions
{
    public const All = 'Blog.All';
    public const Index = 'Blog.Index';
    public const Show = 'Blog.Show';
    public const Store = 'Blog.Store';
    public const Update = 'Blog.Update';
    public const Toggle = 'Blog.Toggle';
    public const Delete = 'Blog.Delete';
    public const Restore = 'Blog.Restore';

    protected string $model = Blog::class;
}
