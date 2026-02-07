<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CertificateTemplate;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CertificateTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CertificateTemplate::class, 'Index'));
    }

    public function view(User $user, CertificateTemplate $certificateTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CertificateTemplate::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CertificateTemplate::class, 'Store'));
    }

    public function update(User $user, CertificateTemplate $certificateTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CertificateTemplate::class, 'Update'));
    }

    public function delete(User $user, CertificateTemplate $certificateTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CertificateTemplate::class, 'Delete'));
    }
}
