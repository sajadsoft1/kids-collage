<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Course;

class CoursePermissions extends BasePermissions
{
    public const All     = 'Course.All';
    public const Index   = 'Course.Index';
    public const Show    = 'Course.Show';
    public const Store   = 'Course.Store';
    public const Update  = 'Course.Update';
    public const Toggle  = 'Course.Toggle';
    public const Delete  = 'Course.Delete';
    public const Restore = 'Course.Restore';

    protected string $model = Course::class;
}
