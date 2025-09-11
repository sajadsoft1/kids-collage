<?php

declare(strict_types=1);

namespace App\Repositories\Teammate;

use App\Repositories\BaseRepositoryInterface;

interface TeammateRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
