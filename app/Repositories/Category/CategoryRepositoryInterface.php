<?php

declare(strict_types=1);

namespace App\Repositories\Category;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Category;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
