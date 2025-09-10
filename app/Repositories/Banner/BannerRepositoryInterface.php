<?php

declare(strict_types=1);

namespace App\Repositories\Banner;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Banner;

interface BannerRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
