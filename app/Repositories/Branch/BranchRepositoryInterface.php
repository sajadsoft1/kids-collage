<?php

declare(strict_types=1);

namespace App\Repositories\Branch;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Branch;

interface BranchRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
