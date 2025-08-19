<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

trait StatusTrait
{
    public bool $published = false;

    public array $statusLists = [
        ['id' => 1, 'name' => 'فعال'],
        ['id' => 0, 'name' => 'غیر فعال'],
    ];
}
