<?php

declare(strict_types=1);

namespace App\Repositories\News;

use App\Repositories\BaseRepositoryInterface;
use App\Models\News;

interface NewsRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
