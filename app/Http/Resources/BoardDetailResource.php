<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BoardDetailResource",
 *     title="BoardDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="Project Alpha"),
 *     @OA\Property(property="description", type="string", default="Main project board"),
 *     @OA\Property(property="color", type="string", default="#FF5733"),
 *     @OA\Property(property="is_active", type="boolean", default=true),
 *     @OA\Property(property="system_protected", type="boolean", default=false),
 *     @OA\Property(property="extra_attributes", type="object"),
 *     @OA\Property(property="users", type="array", @OA\Items(ref="#/components/schemas/SimpleUserResource")),
 *     @OA\Property(property="columns", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="cards", type="array", @OA\Items(ref="#/components/schemas/SimpleCardResource")),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class BoardDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = BoardResource::make($this)->toArray($request);

        return array_merge($resource, [
            'extra_attributes' => $this->extra_attributes,
            'users'            => $this->whenLoaded('users', fn () => SimpleUserResource::collection($this->users)),
            'columns'          => $this->whenLoaded('columns', fn () => $this->columns),
            'cards'            => $this->whenLoaded('cards', fn () => SimpleCardResource::collection($this->cards)),
        ]);
    }
}
