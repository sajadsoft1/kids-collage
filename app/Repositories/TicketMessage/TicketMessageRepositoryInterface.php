<?php

declare(strict_types=1);

namespace App\Repositories\TicketMessage;

use App\Repositories\BaseRepositoryInterface;
use App\Models\TicketMessage;

interface TicketMessageRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
