<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\CourseSessionTemplate;

class CourseSessionTemplatePermissions extends BasePermissions
{
    public const All = 'CourseSessionTemplate.All';
    public const Index = 'CourseSessionTemplate.Index';
    public const Show = 'CourseSessionTemplate.Show';
    public const Store = 'CourseSessionTemplate.Store';
    public const Update = 'CourseSessionTemplate.Update';
    public const Toggle = 'CourseSessionTemplate.Toggle';
    public const Delete = 'CourseSessionTemplate.Delete';
    public const Restore = 'CourseSessionTemplate.Restore';

    protected string $model = CourseSessionTemplate::class;
}
