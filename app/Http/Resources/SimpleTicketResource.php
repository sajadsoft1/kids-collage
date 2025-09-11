<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimpleTicketResource",
 *     title="SimpleTicketResource",
 *
 *     @OA\Property(property="id", type="integer", description="Ticket id", example="1"),
 *     @OA\Property(property="subject", type="string", example="Technical Support"),
 *     @OA\Property(property="key", type="string", example="TICKET-123456"),
 *     @OA\Property(property="status", type="string", example="open"),
 *     @OA\Property(property="priority", type="string", example="high"),
 * )
 */
class SimpleTicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'subject'  => $this->subject,
            'key'      => $this->key,
            'status'   => $this->status->value,
            'priority' => $this->priority->value,
        ];
    }
}
