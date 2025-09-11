<?php

declare(strict_types=1);

namespace App\Repositories\Client;

use App\Repositories\BaseRepositoryInterface;

interface ClientRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
