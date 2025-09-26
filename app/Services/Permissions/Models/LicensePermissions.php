<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\License;

class LicensePermissions extends BasePermissions
{
    public const All     = "License.All";
    public const Index   = "License.Index";
    public const Show    = "License.Show";
    public const Store   = "License.Store";
    public const Update  = "License.Update";
    public const Toggle  = "License.Toggle";
    public const Delete  = "License.Delete";
    public const Restore = "License.Restore";

    protected string $model = License::class;
}
