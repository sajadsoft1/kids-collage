<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\PortFolio;

class PortFolioPermissions extends BasePermissions
{
    public const All = 'PortFolio.All';
    public const Index = 'PortFolio.Index';
    public const Show = 'PortFolio.Show';
    public const Store = 'PortFolio.Store';
    public const Update = 'PortFolio.Update';
    public const Toggle = 'PortFolio.Toggle';
    public const Delete = 'PortFolio.Delete';
    public const Restore = 'PortFolio.Restore';

    protected string $model = PortFolio::class;
}
