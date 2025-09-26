<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Payment;

class PaymentPermissions extends BasePermissions
{
    public const All     = "Payment.All";
    public const Index   = "Payment.Index";
    public const Show    = "Payment.Show";
    public const Store   = "Payment.Store";
    public const Update  = "Payment.Update";
    public const Toggle  = "Payment.Toggle";
    public const Delete  = "Payment.Delete";
    public const Restore = "Payment.Restore";

    protected string $model = Payment::class;
}
