<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\QuestionSystem;

class QuestionSystemPermissions extends BasePermissions
{
    public const All     = "QuestionSystem.All";
    public const Index   = "QuestionSystem.Index";
    public const Show    = "QuestionSystem.Show";
    public const Store   = "QuestionSystem.Store";
    public const Update  = "QuestionSystem.Update";
    public const Toggle  = "QuestionSystem.Toggle";
    public const Delete  = "QuestionSystem.Delete";
    public const Restore = "QuestionSystem.Restore";

    protected string $model = QuestionSystem::class;
}
