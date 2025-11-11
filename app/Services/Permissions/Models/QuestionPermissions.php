<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Question;

class QuestionPermissions extends BasePermissions
{
    public const All = 'Question.All';
    public const Index = 'Question.Index';
    public const Show = 'Question.Show';
    public const Store = 'Question.Store';
    public const Update = 'Question.Update';
    public const Toggle = 'Question.Toggle';
    public const Delete = 'Question.Delete';
    public const Restore = 'Question.Restore';

    protected string $model = Question::class;
}
