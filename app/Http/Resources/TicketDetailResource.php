<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TicketDetailResource",
 *     title="TicketDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="subject", type="string", default="Support Request"),
 *     @OA\Property(property="key", type="string", default="TICKET-123456"),
 *     @OA\Property(property="status", ref="#/components/schemas/TicketStatusEnum"),
 *     @OA\Property(property="priority", ref="#/components/schemas/TicketPriorityEnum"),
 *     @OA\Property(property="department", ref="#/components/schemas/TicketDepartmentEnum"),
 *     @OA\Property(property="user", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="unread_messages_count", type="integer", default="2"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="close_by", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="messages", type="array", @OA\Items(ref="#/components/schemas/TicketMessageResource")),
 *
 * )
 */
class TicketDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = TicketResource::make($this)->toArray($request);

        return array_merge($resource, [
            'close_by' => $this->whenLoaded('closeBy', fn () => SimpleUserResource::make($this->closeBy)),
            'messages' => $this->whenLoaded('messages', fn () => TicketMessageResource::collection($this->messages)),
        ]);
    }
}
