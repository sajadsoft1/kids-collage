<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Notebook;

class NotebookPermissions extends BasePermissions
{
    public const All     = "Notebook.All";
    public const Index   = "Notebook.Index";
    public const Show    = "Notebook.Show";
    public const Store   = "Notebook.Store";
    public const Update  = "Notebook.Update";
    public const Toggle  = "Notebook.Toggle";
    public const Delete  = "Notebook.Delete";
    public const Restore = "Notebook.Restore";

    protected string $model = Notebook::class;
}
