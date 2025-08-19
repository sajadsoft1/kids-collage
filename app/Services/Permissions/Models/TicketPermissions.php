<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Ticket;

class TicketPermissions extends BasePermissions
{
    public const All     = 'Ticket.All';
    public const Index   = 'Ticket.Index';
    public const Show    = 'Ticket.Show';
    public const Store   = 'Ticket.Store';
    public const Update  = 'Ticket.Update';
    public const Toggle  = 'Ticket.Toggle';
    public const Delete  = 'Ticket.Delete';
    public const Restore = 'Ticket.Restore';

    protected string $model = Ticket::class;
}
