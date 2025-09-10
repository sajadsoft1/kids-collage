<?php

declare(strict_types=1);

namespace App\Repositories\Opinion;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Opinion;

interface OpinionRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
