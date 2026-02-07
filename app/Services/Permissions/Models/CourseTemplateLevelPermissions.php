<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\CourseTemplateLevel;

class CourseTemplateLevelPermissions extends BasePermissions
{
    public const All = 'CourseTemplateLevel.All';
    public const Index = 'CourseTemplateLevel.Index';
    public const Show = 'CourseTemplateLevel.Show';
    public const Store = 'CourseTemplateLevel.Store';
    public const Update = 'CourseTemplateLevel.Update';
    public const Toggle = 'CourseTemplateLevel.Toggle';
    public const Delete = 'CourseTemplateLevel.Delete';
    public const Restore = 'CourseTemplateLevel.Restore';

    protected string $model = CourseTemplateLevel::class;
}
