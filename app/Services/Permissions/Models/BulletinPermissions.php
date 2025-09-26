<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Bulletin;

class BulletinPermissions extends BasePermissions
{
    public const All     = "Bulletin.All";
    public const Index   = "Bulletin.Index";
    public const Show    = "Bulletin.Show";
    public const Store   = "Bulletin.Store";
    public const Update  = "Bulletin.Update";
    public const Toggle  = "Bulletin.Toggle";
    public const Delete  = "Bulletin.Delete";
    public const Restore = "Bulletin.Restore";

    protected string $model = Bulletin::class;
}
