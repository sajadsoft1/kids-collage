<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TicketResource",
 *     title="TicketResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="subject", type="string", default="Support Request"),
 *     @OA\Property(property="key", type="string", default="TICKET-123456"),
 *     @OA\Property(property="status", type="string", default="open"),
 *     @OA\Property(property="priority", type="string", default="high"),
 *     @OA\Property(property="department", type="string", default="technical"),
 *     @OA\Property(property="user", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="unread_messages_count", type="integer", default="2"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'subject'               => $this->subject,
            'key'                   => $this->key,
            'status'                => $this->status->value,
            'priority'              => $this->priority->value,
            'department'            => $this->department->value,
            'user'                  => $this->whenLoaded('user', fn () => SimpleUserResource::make($this->user)),
            'unread_messages_count' => $this->unread_messages_count,
            'updated_at'            => $this->updated_at,
            'created_at'            => $this->created_at,
        ];
    }
}
