<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TicketMessageResource",
 *     title="TicketMessageResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="message", type="string", default="Issue resolved"),
 *     @OA\Property(property="is_from_admin", type="boolean", default=false),
 *     @OA\Property(property="read_by", type="integer", nullable=true),
 *     @OA\Property(property="ticket", ref="#/components/schemas/SimpleTicketResource"),
 *     @OA\Property(property="user", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class TicketMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'message'       => $this->message,
            'is_from_admin' => $this->is_from_admin,
            'read_by'       => $this->read_by,
            'ticket'        => $this->whenLoaded('ticket', fn () => SimpleTicketResource::make($this->ticket)),
            'user'          => $this->whenLoaded('user', fn () => SimpleUserResource::make($this->user)),
            'updated_at'    => $this->updated_at,
            'created_at'    => $this->created_at,
        ];
    }
}
