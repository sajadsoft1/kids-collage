<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Room;

class RoomPermissions extends BasePermissions
{
    public const All     = "Room.All";
    public const Index   = "Room.Index";
    public const Show    = "Room.Show";
    public const Store   = "Room.Store";
    public const Update  = "Room.Update";
    public const Toggle  = "Room.Toggle";
    public const Delete  = "Room.Delete";
    public const Restore = "Room.Restore";

    protected string $model = Room::class;
}
