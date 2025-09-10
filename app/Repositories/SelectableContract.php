<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;

interface SelectableContract
{
    public function select(array $payload=[]): Collection;
}
