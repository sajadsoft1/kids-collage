<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use Karnoweb\LaravelNotification\Models\NotificationTemplate;

class NotificationTemplatePermissions extends BasePermissions
{
    public const All = 'NotificationTemplate.All';
    public const Index = 'NotificationTemplate.Index';
    public const Show = 'NotificationTemplate.Show';
    public const Store = 'NotificationTemplate.Store';
    public const Update = 'NotificationTemplate.Update';
    public const Toggle = 'NotificationTemplate.Toggle';
    public const Delete = 'NotificationTemplate.Delete';
    public const Restore = 'NotificationTemplate.Restore';

    protected string $model = NotificationTemplate::class;
}
