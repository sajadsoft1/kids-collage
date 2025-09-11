<?php

declare(strict_types=1);

namespace App\Repositories\Page;

use App\Repositories\BaseRepositoryInterface;

interface PageRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
