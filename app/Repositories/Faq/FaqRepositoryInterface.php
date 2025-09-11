<?php

declare(strict_types=1);

namespace App\Repositories\Faq;

use App\Repositories\BaseRepositoryInterface;

interface FaqRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
