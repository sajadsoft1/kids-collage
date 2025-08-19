<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Comment;

class CommentPermissions extends BasePermissions
{
    public const All     = 'Comment.All';
    public const Index   = 'Comment.Index';
    public const Show    = 'Comment.Show';
    public const Store   = 'Comment.Store';
    public const Update  = 'Comment.Update';
    public const Toggle  = 'Comment.Toggle';
    public const Delete  = 'Comment.Delete';
    public const Restore = 'Comment.Restore';

    protected string $model = Comment::class;
}
