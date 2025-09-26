<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Installment;

class InstallmentPermissions extends BasePermissions
{
    public const All     = "Installment.All";
    public const Index   = "Installment.Index";
    public const Show    = "Installment.Show";
    public const Store   = "Installment.Store";
    public const Update  = "Installment.Update";
    public const Toggle  = "Installment.Toggle";
    public const Delete  = "Installment.Delete";
    public const Restore = "Installment.Restore";

    protected string $model = Installment::class;
}
