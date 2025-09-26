<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Order;

class OrderPermissions extends BasePermissions
{
    public const All     = "Order.All";
    public const Index   = "Order.Index";
    public const Show    = "Order.Show";
    public const Store   = "Order.Store";
    public const Update  = "Order.Update";
    public const Toggle  = "Order.Toggle";
    public const Delete  = "Order.Delete";
    public const Restore = "Order.Restore";

    protected string $model = Order::class;
}
