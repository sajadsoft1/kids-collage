<?php

declare(strict_types=1);

namespace App\Repositories\Portfolio;

use App\Repositories\BaseRepositoryInterface;

interface PortfolioRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
