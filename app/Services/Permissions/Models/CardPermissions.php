<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Card;

class CardPermissions extends BasePermissions
{
    public const All     = "Card.All";
    public const Index   = "Card.Index";
    public const Show    = "Card.Show";
    public const Store   = "Card.Store";
    public const Update  = "Card.Update";
    public const Toggle  = "Card.Toggle";
    public const Delete  = "Card.Delete";
    public const Restore = "Card.Restore";

    protected string $model = Card::class;
}
