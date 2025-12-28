<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Event;

class EventPermissions extends BasePermissions
{
    public const All = 'Event.All';
    public const Index = 'Event.Index';
    public const Show = 'Event.Show';
    public const Store = 'Event.Store';
    public const Update = 'Event.Update';
    public const Toggle = 'Event.Toggle';
    public const Delete = 'Event.Delete';
    public const Restore = 'Event.Restore';

    protected string $model = Event::class;
}
