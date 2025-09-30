<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Term;

class TermPermissions extends BasePermissions
{
    public const All     = "Term.All";
    public const Index   = "Term.Index";
    public const Show    = "Term.Show";
    public const Store   = "Term.Store";
    public const Update  = "Term.Update";
    public const Toggle  = "Term.Toggle";
    public const Delete  = "Term.Delete";
    public const Restore = "Term.Restore";

    protected string $model = Term::class;
}
