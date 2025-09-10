<?php

declare(strict_types=1);

namespace App\Repositories\Portfolio;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Portfolio;

interface PortfolioRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
