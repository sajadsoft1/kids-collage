<?php

declare(strict_types=1);

namespace App\Repositories\CardFlow;

use App\Repositories\BaseRepositoryInterface;
use App\Models\CardFlow;

interface CardFlowRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
