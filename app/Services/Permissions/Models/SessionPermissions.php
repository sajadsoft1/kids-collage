<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\CourseSession;

class SessionPermissions extends BasePermissions
{
    public const All     = "Session.All";
    public const Index   = "Session.Index";
    public const Show    = "Session.Show";
    public const Store   = "Session.Store";
    public const Update  = "Session.Update";
    public const Toggle  = "Session.Toggle";
    public const Delete  = "Session.Delete";
    public const Restore = "Session.Restore";

    protected string $model = CourseSession::class;
}
