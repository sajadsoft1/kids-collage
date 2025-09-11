<?php

declare(strict_types=1);

namespace App\Repositories\Role;

use App\Repositories\BaseRepositoryInterface;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
