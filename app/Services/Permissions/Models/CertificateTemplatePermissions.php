<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\CertificateTemplate;

class CertificateTemplatePermissions extends BasePermissions
{
    public const All = 'CertificateTemplate.All';

    public const Index = 'CertificateTemplate.Index';

    public const Show = 'CertificateTemplate.Show';

    public const Store = 'CertificateTemplate.Store';

    public const Update = 'CertificateTemplate.Update';

    public const Toggle = 'CertificateTemplate.Toggle';

    public const Delete = 'CertificateTemplate.Delete';

    public const Restore = 'CertificateTemplate.Restore';

    protected string $model = CertificateTemplate::class;
}
