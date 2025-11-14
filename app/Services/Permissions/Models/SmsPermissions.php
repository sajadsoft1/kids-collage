<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Sms;

class SmsPermissions extends BasePermissions
{
    public const All = 'Sms.All';
    public const Index = 'Sms.Index';
    public const Show = 'Sms.Show';
    public const Store = 'Sms.Store';
    public const Update = 'Sms.Update';
    public const Toggle = 'Sms.Toggle';
    public const Delete = 'Sms.Delete';
    public const Restore = 'Sms.Restore';

    protected string $model = Sms::class;
}
