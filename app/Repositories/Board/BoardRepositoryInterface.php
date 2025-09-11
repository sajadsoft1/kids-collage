<?php

declare(strict_types=1);

namespace App\Repositories\Board;

use App\Repositories\BaseRepositoryInterface;

interface BoardRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
