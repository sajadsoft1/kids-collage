<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Page;

class PagePermissions extends BasePermissions
{
    public const All     = 'Page.All';
    public const Index   = 'Page.Index';
    public const Show    = 'Page.Show';
    public const Store   = 'Page.Store';
    public const Update  = 'Page.Update';
    public const Toggle  = 'Page.Toggle';
    public const Delete  = 'Page.Delete';
    public const Restore = 'Page.Restore';

    protected string $model = Page::class;
}
