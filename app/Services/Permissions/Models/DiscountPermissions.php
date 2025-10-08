<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Discount;

class DiscountPermissions extends BasePermissions
{
    public const All     = "Discount.All";
    public const Index   = "Discount.Index";
    public const Show    = "Discount.Show";
    public const Store   = "Discount.Store";
    public const Update  = "Discount.Update";
    public const Toggle  = "Discount.Toggle";
    public const Delete  = "Discount.Delete";
    public const Restore = "Discount.Restore";

    protected string $model = Discount::class;
}
