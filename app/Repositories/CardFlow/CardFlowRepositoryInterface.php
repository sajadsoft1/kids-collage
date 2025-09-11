<?php

declare(strict_types=1);

namespace App\Repositories\CardFlow;

use App\Repositories\BaseRepositoryInterface;

interface CardFlowRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
