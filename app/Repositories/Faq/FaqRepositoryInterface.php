<?php

declare(strict_types=1);

namespace App\Repositories\Faq;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Faq;

interface FaqRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
