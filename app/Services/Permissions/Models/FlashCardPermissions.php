<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\FlashCard;

class FlashCardPermissions extends BasePermissions
{
    public const All = 'FlashCard.All';
    public const Index = 'FlashCard.Index';
    public const Show = 'FlashCard.Show';
    public const Store = 'FlashCard.Store';
    public const Update = 'FlashCard.Update';
    public const Toggle = 'FlashCard.Toggle';
    public const Delete = 'FlashCard.Delete';
    public const Restore = 'FlashCard.Restore';

    protected string $model = FlashCard::class;
}
