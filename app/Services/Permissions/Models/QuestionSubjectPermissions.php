<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\QuestionSubject;

class QuestionSubjectPermissions extends BasePermissions
{
    public const All     = 'QuestionSubject.All';
    public const Index   = 'QuestionSubject.Index';
    public const Show    = 'QuestionSubject.Show';
    public const Store   = 'QuestionSubject.Store';
    public const Update  = 'QuestionSubject.Update';
    public const Toggle  = 'QuestionSubject.Toggle';
    public const Delete  = 'QuestionSubject.Delete';
    public const Restore = 'QuestionSubject.Restore';

    protected string $model = QuestionSubject::class;
}
