<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\CourseSession;

class CourseSessionPermissions extends BasePermissions
{
    public const All = 'CourseSession.All';
    public const Index = 'CourseSession.Index';
    public const Show = 'CourseSession.Show';
    public const Store = 'CourseSession.Store';
    public const Update = 'CourseSession.Update';
    public const Toggle = 'CourseSession.Toggle';
    public const Delete = 'CourseSession.Delete';
    public const Restore = 'CourseSession.Restore';

    protected string $model = CourseSession::class;
}
