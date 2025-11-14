<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\ContactUs;

class ContactUsPermissions extends BasePermissions
{
    public const All = 'ContactUs.All';
    public const Index = 'ContactUs.Index';
    public const Show = 'ContactUs.Show';
    public const Store = 'ContactUs.Store';
    public const Update = 'ContactUs.Update';
    public const Toggle = 'ContactUs.Toggle';
    public const Delete = 'ContactUs.Delete';
    public const Restore = 'ContactUs.Restore';

    protected string $model = ContactUs::class;
}
