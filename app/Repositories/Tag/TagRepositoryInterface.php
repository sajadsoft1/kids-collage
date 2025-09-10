<?php

declare(strict_types=1);

namespace App\Repositories\Tag;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Tag;

interface TagRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
