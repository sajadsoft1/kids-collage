<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\QuestionOption;

class QuestionOptionPermissions extends BasePermissions
{
    public const All = 'QuestionOption.All';
    public const Index = 'QuestionOption.Index';
    public const Show = 'QuestionOption.Show';
    public const Store = 'QuestionOption.Store';
    public const Update = 'QuestionOption.Update';
    public const Toggle = 'QuestionOption.Toggle';
    public const Delete = 'QuestionOption.Delete';
    public const Restore = 'QuestionOption.Restore';

    protected string $model = QuestionOption::class;
}
