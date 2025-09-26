<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Enrollment;

class EnrollmentPermissions extends BasePermissions
{
    public const All     = "Enrollment.All";
    public const Index   = "Enrollment.Index";
    public const Show    = "Enrollment.Show";
    public const Store   = "Enrollment.Store";
    public const Update  = "Enrollment.Update";
    public const Toggle  = "Enrollment.Toggle";
    public const Delete  = "Enrollment.Delete";
    public const Restore = "Enrollment.Restore";

    protected string $model = Enrollment::class;
}
