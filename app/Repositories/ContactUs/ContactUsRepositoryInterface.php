<?php

declare(strict_types=1);

namespace App\Repositories\ContactUs;

use App\Repositories\BaseRepositoryInterface;
use App\Models\ContactUs;

interface ContactUsRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
