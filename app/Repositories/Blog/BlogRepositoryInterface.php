<?php

declare(strict_types=1);

namespace App\Repositories\Blog;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Blog;

interface BlogRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
