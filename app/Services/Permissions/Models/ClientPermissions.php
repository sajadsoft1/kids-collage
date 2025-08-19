<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Client;

class ClientPermissions extends BasePermissions
{
    public const All     = 'Client.All';
    public const Index   = 'Client.Index';
    public const Show    = 'Client.Show';
    public const Store   = 'Client.Store';
    public const Update  = 'Client.Update';
    public const Toggle  = 'Client.Toggle';
    public const Delete  = 'Client.Delete';
    public const Restore = 'Client.Restore';

    protected string $model = Client::class;
}
