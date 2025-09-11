<?php

declare(strict_types=1);

namespace App\Repositories\Category;

use App\Repositories\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
