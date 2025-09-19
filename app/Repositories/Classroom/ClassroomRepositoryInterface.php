<?php

declare(strict_types=1);

namespace App\Repositories\Classroom;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Classroom;

interface ClassroomRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
