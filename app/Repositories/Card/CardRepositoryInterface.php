<?php

declare(strict_types=1);

namespace App\Repositories\Card;

use App\Repositories\BaseRepositoryInterface;

interface CardRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
