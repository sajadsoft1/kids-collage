<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Classroom;

class ClassroomPermissions extends BasePermissions
{
    public const All     = "Classroom.All";
    public const Index   = "Classroom.Index";
    public const Show    = "Classroom.Show";
    public const Store   = "Classroom.Store";
    public const Update  = "Classroom.Update";
    public const Toggle  = "Classroom.Toggle";
    public const Delete  = "Classroom.Delete";
    public const Restore = "Classroom.Restore";

    protected string $model = Classroom::class;
}
