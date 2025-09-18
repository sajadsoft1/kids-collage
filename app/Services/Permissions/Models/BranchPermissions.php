<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Branch;

class BranchPermissions extends BasePermissions
{
    public const All     = "Branch.All";
    public const Index   = "Branch.Index";
    public const Show    = "Branch.Show";
    public const Store   = "Branch.Store";
    public const Update  = "Branch.Update";
    public const Toggle  = "Branch.Toggle";
    public const Delete  = "Branch.Delete";
    public const Restore = "Branch.Restore";

    protected string $model = Branch::class;
}
