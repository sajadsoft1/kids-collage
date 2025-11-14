<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Faq;

class FaqPermissions extends BasePermissions
{
    public const All = 'Faq.All';
    public const Index = 'Faq.Index';
    public const Show = 'Faq.Show';
    public const Store = 'Faq.Store';
    public const Update = 'Faq.Update';
    public const Toggle = 'Faq.Toggle';
    public const Delete = 'Faq.Delete';
    public const Restore = 'Faq.Restore';

    protected string $model = Faq::class;
}
