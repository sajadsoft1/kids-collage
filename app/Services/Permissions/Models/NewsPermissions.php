<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\News;

class NewsPermissions extends BasePermissions
{
    public const All     = "News.All";
    public const Index   = "News.Index";
    public const Show    = "News.Show";
    public const Store   = "News.Store";
    public const Update  = "News.Update";
    public const Toggle  = "News.Toggle";
    public const Delete  = "News.Delete";
    public const Restore = "News.Restore";

    protected string $model = News::class;
}
