<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Exam;

class ExamPermissions extends BasePermissions
{
    public const All = 'Exam.All';
    public const Index = 'Exam.Index';
    public const Show = 'Exam.Show';
    public const Store = 'Exam.Store';
    public const Update = 'Exam.Update';
    public const Toggle = 'Exam.Toggle';
    public const Delete = 'Exam.Delete';
    public const Restore = 'Exam.Restore';

    protected string $model = Exam::class;
}
