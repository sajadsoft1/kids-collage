<?php

declare(strict_types=1);

namespace App\Repositories\Slider;

use App\Repositories\BaseRepositoryInterface;

interface SliderRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
