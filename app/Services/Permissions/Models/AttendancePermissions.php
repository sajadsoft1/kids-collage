<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Attendance;

class AttendancePermissions extends BasePermissions
{
    public const All     = 'Attendance.All';
    public const Index   = 'Attendance.Index';
    public const Show    = 'Attendance.Show';
    public const Store   = 'Attendance.Store';
    public const Update  = 'Attendance.Update';
    public const Toggle  = 'Attendance.Toggle';
    public const Delete  = 'Attendance.Delete';
    public const Restore = 'Attendance.Restore';

    protected string $model = Attendance::class;
}
