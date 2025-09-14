<?php

namespace App\Actions\Ticket;

use App\Enums\TicketDepartmentEnum;
use App\Enums\TicketPriorityEnum;
use App\Enums\TicketStatusEnum;
use Lorisleiva\Actions\Concerns\AsAction;

class DataTicketAction
{
    use AsAction;

    public function handle(array $payload = []): array
    {
        return [
            'departments' => TicketDepartmentEnum::options(),
            'priorities'  => TicketPriorityEnum::options(),
            'statuses'    => TicketStatusEnum::options(),
        ];
    }
}
