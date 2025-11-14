<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\QuestionCompetency;

class QuestionCompetencyPermissions extends BasePermissions
{
    public const All = 'QuestionCompetency.All';
    public const Index = 'QuestionCompetency.Index';
    public const Show = 'QuestionCompetency.Show';
    public const Store = 'QuestionCompetency.Store';
    public const Update = 'QuestionCompetency.Update';
    public const Toggle = 'QuestionCompetency.Toggle';
    public const Delete = 'QuestionCompetency.Delete';
    public const Restore = 'QuestionCompetency.Restore';

    protected string $model = QuestionCompetency::class;
}
