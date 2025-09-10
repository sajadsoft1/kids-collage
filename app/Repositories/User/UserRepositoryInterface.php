<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
