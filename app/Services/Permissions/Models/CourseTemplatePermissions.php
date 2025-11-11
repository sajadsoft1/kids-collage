<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\CourseTemplate;

class CourseTemplatePermissions extends BasePermissions
{
    public const All = 'CourseTemplate.All';
    public const Index = 'CourseTemplate.Index';
    public const Show = 'CourseTemplate.Show';
    public const Store = 'CourseTemplate.Store';
    public const Update = 'CourseTemplate.Update';
    public const Toggle = 'CourseTemplate.Toggle';
    public const Delete = 'CourseTemplate.Delete';
    public const Restore = 'CourseTemplate.Restore';

    protected string $model = CourseTemplate::class;
}
