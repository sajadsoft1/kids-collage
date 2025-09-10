<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\TicketMessage;

class TicketMessagePermissions extends BasePermissions
{
    public const All     = "TicketMessage.All";
    public const Index   = "TicketMessage.Index";
    public const Show    = "TicketMessage.Show";
    public const Store   = "TicketMessage.Store";
    public const Update  = "TicketMessage.Update";
    public const Toggle  = "TicketMessage.Toggle";
    public const Delete  = "TicketMessage.Delete";
    public const Restore = "TicketMessage.Restore";

    protected string $model = TicketMessage::class;
}
