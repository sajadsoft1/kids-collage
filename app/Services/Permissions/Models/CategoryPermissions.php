<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Category;

class CategoryPermissions extends BasePermissions
{
    public const All     = 'Category.All';
    public const Index   = 'Category.Index';
    public const Show    = 'Category.Show';
    public const Store   = 'Category.Store';
    public const Update  = 'Category.Update';
    public const Toggle  = 'Category.Toggle';
    public const Delete  = 'Category.Delete';
    public const Restore = 'Category.Restore';

    protected string $model = Category::class;
}
