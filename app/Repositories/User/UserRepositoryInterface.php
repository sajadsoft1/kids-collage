<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;
use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
