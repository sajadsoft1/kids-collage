<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Board;

class BoardPermissions extends BasePermissions
{
    public const All     = 'Board.All';
    public const Index   = 'Board.Index';
    public const Show    = 'Board.Show';
    public const Store   = 'Board.Store';
    public const Update  = 'Board.Update';
    public const Toggle  = 'Board.Toggle';
    public const Delete  = 'Board.Delete';
    public const Restore = 'Board.Restore';

    protected string $model = Board::class;
}
