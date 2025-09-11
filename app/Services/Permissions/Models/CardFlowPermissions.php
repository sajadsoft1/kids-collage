<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\CardFlow;

class CardFlowPermissions extends BasePermissions
{
    public const All     = 'CardFlow.All';
    public const Index   = 'CardFlow.Index';
    public const Show    = 'CardFlow.Show';
    public const Store   = 'CardFlow.Store';
    public const Update  = 'CardFlow.Update';
    public const Toggle  = 'CardFlow.Toggle';
    public const Delete  = 'CardFlow.Delete';
    public const Restore = 'CardFlow.Restore';

    protected string $model = CardFlow::class;
}
