<?php

declare(strict_types=1);

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Ticket;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
